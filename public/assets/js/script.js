document.getElementById('assignForm').addEventListener('submit', function (e) {
    e.preventDefault();
    var postId = this.dataset.postId;
    var formData = new FormData(this);

    fetch('/posts/assign/' + postId, {
        method: 'POST',
        body: formData
    }).then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                closeModal();
                alert('Platforms updated');
            } else {
                alert(data.message || 'Update failed');
            }
        });
});

function openAssignModal(postId, postTitle) {
    document.getElementById('modalTitle').innerText = postTitle;
    var form = document.getElementById('assignForm');
    form.dataset.postId = postId;

    // Fetch platforms + assigned via AJAX
    fetch('/posts/assign/' + postId)
        .then(res => res.json())  // <-- parse JSON
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('platformCheckboxes').innerHTML = data.html; // use html property
                document.getElementById('assignModal').style.display = 'block';
            } else {
                alert('Failed to load platforms');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Something went wrong');
        });
}

function closeModal() {
    document.getElementById('assignModal').style.display = 'none';
}


var el = document.getElementById('posts-table');
// var sortable = Sortable.create(el, {
//     animation: 150,
//     onEnd: function (evt) {
//         // Gather new order
//         var order = [];
//         el.querySelectorAll('tr').forEach((tr, index) => {
//             order.push({
//                 id: tr.getAttribute('data-id'),
//                 priority: index + 1
//             });
//             // Update priority cell immediately
//             tr.querySelector('.priority').innerText = index + 1;
//         });

//         // Send AJAX to backend
//         fetch('/posts/reorder', {
//             method: 'POST',
//             headers: { 'Content-Type': 'application/json' },
//             body: JSON.stringify({ order: order })
//         }).then(res => res.json())
//             .then(data => {
//                 if (data.status !== 'success') {
//                     alert('Priority update failed!');
//                 }
//             });
//     }
// });

var sortable = Sortable.create(el, {
    animation: 150,
    onEnd: function (evt) {

        var order = [];

        el.querySelectorAll('tr').forEach((tr, index) => {
            let globalPriority = startOffset + index + 1;

            order.push({
                id: tr.getAttribute('data-id'),
                priority: globalPriority
            });

            tr.querySelector('.priority').innerText = globalPriority;
        });

        fetch('/posts/reorder', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ order: order })
        });
    }
});
document.querySelectorAll('.delete-post').forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        if (!confirm('Are you sure to delete this post?')) return;

        var id = this.dataset.id;
        var loader = document.getElementById('loader');

        loader.style.display = 'block'; // show loader

        fetch('/posts/delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id
        }).then(res => res.json())
            .then(data => {
                loader.style.display = 'none'; // hide loader
                if (data.status === 'success') {
                    location.reload(); // refresh table / pagination
                } else {
                    alert('Delete failed!');
                }
            }).catch(err => {
                loader.style.display = 'none';
                alert('Something went wrong!');
            });
    });
});
