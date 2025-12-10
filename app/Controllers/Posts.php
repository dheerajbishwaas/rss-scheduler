<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\PlatformModel;
use App\Models\PostPlatformModel;

class Posts extends BaseController
{
    public function index()
    {
        $model = new PostModel();

        $perPage = 10; // pagination limit

        $data['posts'] = $model
            ->orderBy('priority', 'ASC')  // priority wise listing
            ->paginate($perPage);

        $data['pager'] = $model->pager;
        $data['currentPage'] = $model->pager->getCurrentPage();
        $data['perPage'] = $model->pager->getPerPage();
        return view('posts/index', $data);
    }

    public function dashboard()
{
    $platformModel = new PlatformModel();
    $platforms = $platformModel->findAll();

    $platformId = $this->request->getGet('platform') ?? null;
    $postModel = new PostModel();
    $postPlatformModel = new PostPlatformModel();

    if ($platformId) {
        // Filter posts by selected platform
        $builder = $postModel->builder()
            ->select('posts.*')
            ->join('post_platform', 'posts.id = post_platform.post_id')
            ->where('post_platform.platform_id', $platformId)
            ->orderBy('posts.priority', 'ASC');

        $posts = $postModel->paginate(10);
        $pager = $postModel->pager;
    } else {
        // All posts assigned to any platform
        $builder = $postModel->builder()
            ->select('posts.*')
            ->join('post_platform', 'posts.id = post_platform.post_id')
            ->groupBy('posts.id')
            ->orderBy('posts.priority','ASC');

        $posts = $postModel->paginate(10);
        $pager = $postModel->pager;
    }

    // Fetch all assigned platforms for these posts
    $assignedPlatforms = [];
    $postIds = array_column($posts, 'id');
    if (!empty($postIds)) {
        $assignedPlatformsRaw = $postPlatformModel
            ->select('post_platform.post_id, platforms.name')
            ->join('platforms', 'platforms.id = post_platform.platform_id')
            ->whereIn('post_platform.post_id', $postIds)
            ->findAll();

        // Group platforms by post_id
        $assignedPlatforms = [];
        foreach ($assignedPlatformsRaw as $ap) {
            $assignedPlatforms[$ap['post_id']][] = $ap['name'];
        }
    }
    $data = [
        'posts' => $posts,
        'pager' => $pager,
        'platforms' => $platforms,
        'selectedPlatform' => $platformId,
        'assignedPlatforms' => $assignedPlatforms
    ];

    return view('posts/dashboard', $data);
}



    public function reorder()
    {
        $request = $this->request->getJSON(true); // get JSON array
        if (!$request || !isset($request['order'])) return $this->response->setJSON(['status' => 'error']);

        $model = new PostModel();
        foreach ($request['order'] as $item) {
            $model->update($item['id'], ['priority' => $item['priority']]);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function delete()
    {
        $id = $this->request->getPost('id');
        if (!$id) return $this->response->setJSON(['status' => 'error']);

        $postModel = new PostModel();

        // Get deleted post priority
        $deletedPost = $postModel->find($id);
        if (!$deletedPost) return $this->response->setJSON(['status' => 'error']);

        $deletedPriority = $deletedPost['priority'];

        // Delete post
        $postModel->delete($id);

        // Shift priorities of remaining posts
        $posts = $postModel->where('priority >', $deletedPriority)
            ->orderBy('priority', 'ASC')
            ->findAll();

        foreach ($posts as $p) {
            $postModel->update($p['id'], ['priority' => $p['priority'] - 1]);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function assign($postId)
    {
        $platformModel = new PlatformModel();
        $postPlatformModel = new PostPlatformModel();

        $platforms = $platformModel->findAll();
        $assigned = $postPlatformModel->where('post_id', $postId)->findAll();
        $assignedIds = array_column($assigned, 'platform_id');

        // Return only checkbox HTML
        $html = "";

        foreach ($platforms as $p) {
            $checked = in_array($p['id'], $assignedIds) ? 'checked' : '';
            $html .= "
            <label style='display:block;margin-bottom:5px'>
                <input type='checkbox' name='platforms[]' value='{$p['id']}' $checked>
                {$p['name']}
            </label>
        ";
        }

        return $this->response->setJSON([
            'status' => 'success',
            'html' => $html
        ]);
    }


    public function saveAssignment($postId)
    {
        $selected = $this->request->getPost('platforms') ?? [];

        $post = (new PostModel())->find($postId);

        // Twitter/X validation
        if (in_array(2, $selected) && $post['char_count'] > 280) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Post exceeds 280 chars for X (Twitter)'
            ]);
        }

        $postPlatformModel = new PostPlatformModel();

        // Delete old assignments
        $postPlatformModel->where('post_id', $postId)->delete();

        // Insert new
        foreach ($selected as $platformId) {
            $postPlatformModel->insert([
                'post_id' => $postId,
                'platform_id' => $platformId
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Platforms updated'
        ]);
    }
}
