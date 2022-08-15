<div>
    <!-- ______________INCLUDE FORM "NEW POST"______________ -->
    <?php view_include('newpost'); ?>
    
    <!-- ______________INCLUDE FORM "NEW COMMENT"______________ -->
    <?php view_include('newcomment'); ?>
    
    <!-- ______________INCLUDE FORM "ABOUT"______________ -->
    <?php view_include('about'); ?>

    <div class="position-sticky top-0 w-100 p-2 border border-dark bg-light">
        <!-- Filter -->
        <div>
            <span>Filter by rating: </span>

            <input type="radio" id="filterNegative" name="filter" value="negative" onclick="setfilter(this.value)"  <?= $_SESSION['filter'] == 'negative' ? 'checked' : '' ?>>
            <label for="filterNegative">Negative</label>

            <input type="radio" id="filterAll" name="filter" value="all"  onclick="setfilter(this.value)" <?= $_SESSION['filter'] == 'all' ? 'checked' : '' ?>>
            <label for="filterAll">All</label>

            <input type="radio" id="filterPositive" name="filter" value="positive" onclick="setfilter(this.value)"  <?= $_SESSION['filter'] == 'positive' ? 'checked' : '' ?>>
            <label for="filterPositive">Positive</label>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <?php 
                    for ($page=1; $page <= $data['pages']; $page++) {
                        echo '<li class="page-item"><a class="page-link" href="/posts/page/' . $page . '">' . $page . '</a></li>';
                    }            
                ?>
            </ul>
        </nav>

        <div class="position-absolute bottom-50 end-0">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newPostForm">
                Add post
            </button> 
            <button type="button" class="btn btn-primary m-1" data-bs-toggle="modal" data-bs-target="#aboutWindow">
                About
            </button> 
        </div>
    </div>
    
    <div id="posts" class="text-white">

    </div>

    <style>
        .star {
            text-decoration: none;
            color: white;
        }

        .star:hover {
            color: orange;
            cursor: pointer;
        }
    </style>

    <script>
        let url = new URL(window.location.href);
        let currentPage = url.searchParams.get("page") ? url.searchParams.get("page") : 1;
        let user_comments = <?= '[' . join(', ', $_SESSION['comments']) . "]\n" ?>
        let user_posts = <?= '[' . join(', ', $_SESSION['posts']) . ']' ?>

        // when page loaded
        $(function() {
            bindPageClickEvent()
            setupFormNewPost ()
            setupFormNewComment()
            switchOnPage(currentPage)
        })

        function switchOnPage(page) {
            pages = $('ul.pagination > li').length
            if (page > pages) {
                page = pages
            }
            $('ul.pagination > li:nth-child(' + page + ') > a').trigger( 'click' )
        }

        function bindPageClickEvent() {
            $(".pagination a").click(function( event ) {
                event.preventDefault()
                fetch(this.href)
                .then((response)  => {
                    return response.json()
                })
                .then( (data) => {
                    currentPage = this.text
                    let uri = document.location.origin + document.location.pathname + '?page=' + currentPage

                    window.history.replaceState('a', 'a', uri)

                    reloadPagination()
                    refreshPosts(data)
                })
            })
        }

        // Refresh Posts
        function refreshPosts(data) {
            $('#posts').html('')
            data.forEach( (post) => {
                $('#posts').append('<div class="m-2 p-2" style="background-color:#22B696;" >'
                +   `<div class="p-2 rounded-3" style="background-color:#7B7D7D;">`
                +       `<p>${user_posts.includes(post.id) ? '<span class="text-warning fst-italic">(you)</span>' : ''}  ${post.user_name}, <span class="fst-italic">${parsePHPDateTime(post.created_at)}</span></p>`
                +       `<p>${post.content}</p>`
                +       `<p>
                            <a class="star" data-number=1 data-post_id=${post.id}>☆</a>
                            <a class="star" data-number=2 data-post_id=${post.id}>☆</a>
                            <a class="star" data-number=3 data-post_id=${post.id}>☆</a>
                            <a class="star" data-number=4 data-post_id=${post.id}>☆</a>
                            <a class="star" data-number=5 data-post_id=${post.id}>☆</a>
                            <span class="fs-6 fst-italic">(${post.rate.toFixed(1)})</span>
                        </p>
                        <div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCommentForm" onclick="$('#newCommentForm > form > div > div.modal-body.position-relative.py-4 > input[type=hidden]').val(${post.id})">
                                Add comment
                            </button> 
                        </div>`
                +    `</div>`
                + post.comments.map(comment => {         
                        return `<div class="my-2 ms-3 p-3 rounded-3" style="background-color:#7B7D7D;" data-comment_id=${comment.id}>`
                        +    `<p>${user_comments.includes(comment.id) ? '<span class="text-warning fst-italic">(you)</span>' : ''}  ${comment.user_name}, ${comment.created_at}</p>`
                        +    `<p>${comment.content}</p>`
                        + '</div>'
                }).join('')
                +   `</div>`)                        
            })
            bindStarClickEvent()
        }

        // Reload Pagination
        function reloadPagination() {
            fetch('/posts/pages')
            .then((response) => {
                return response.json()
            })
            .then((pages) => {
                $(".pagination").html('')
                for (let page = 1; page <= pages; page++) {
                    $(".pagination").append(`<li class="page-item"><a class="page-link" href="/posts/page/${page}">${page}</a></li>`)
                }
                bindPageClickEvent()
                if (currentPage > pages) {
                    currentPage = pages
                    switchOnPage(currentPage)
                }
                $(`.pagination > li:nth-child(${currentPage})`).attr('aria-current', 'page')
                $(`.pagination > li:nth-child(${currentPage})`).addClass('active')
            })
        }

        // New post form: Submit button
        function addNewPost() {
            let user_name = $('#newPostForm input[name="user_name"]')[0].value
            let content = $('#newPostForm textarea[name="content"]')[0].value
            if (user_name && content) {
                let data = {
                    'user_name': user_name
                }
                data = $('#newPostForm > form').serialize()
                $.post('/posts/new/store', data)
                .then(d => {
                    user_posts.push(parseInt(d))
                })
                user_posts.push()
                //close form
                $('#newPostForm [data-bs-dismiss="modal"]').trigger('click')
                reloadPagination()
                switchOnPage(currentPage)
                // ++itemsAmount
            }
        }

        // New comment form: Submit button
        function addNewComment() {
            let user_name = $('#newCommentForm input[name="user_name"]')[0].value
            let content = $('#newCommentForm textarea[name="content"]')[0].value
            if (user_name && content) {
                let data = {
                    'user_name': user_name
                }
                data = $('#newCommentForm > form').serialize()
                $.post('/comments/new/store', data)
                .then(d => {
                    user_comments.push(parseInt(d))
                })
                //close form
                $('#newCommentForm [data-bs-dismiss="modal"]').trigger('click')
                reloadPagination()
                switchOnPage(currentPage)
            }
        }

        function checkIfOnLastPage() {
            return $('.pagination a').last().text() == currentPage.toString()
        }

        function parsePHPDateTime(dt) {
            let v1 = new Date(dt)
            return v1.toLocaleString()
        }

        async function performRateOfPost(url, data) {
            const response = await fetch(url, {
                method: 'POST',
                cache: 'no-cache',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                redirect: 'follow', 
                referrerPolicy: 'no-referrer', 
                body: data 
            })

            return await response.json()
        }

        function bindStarClickEvent(rate_data) {
            
            $(".star").click(function( event ) {
                event.preventDefault()

                $.post('/posts/rate', { 
                    rate: this.dataset.number, 
                    post_id: this.dataset.post_id 
                })                
                .then((data) => {
                    rate = JSON.parse(data).rate
                    $(this.parentElement).children('span').text(`(${rate.toPrecision(2)})`)
                })
            })
        }
        
        function setupFormNewPost () {
            // new post form validation
            let formNewPost = $('#newPostForm > form')[0]
            formNewPost.addEventListener('submit', function (event) {
                event.preventDefault()
                if (!formNewPost.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                formNewPost.classList.add('was-validated')
            }, false)
        }
        
        function setupFormNewComment () {
            // form validation
            let formNewComment = $('#newCommentForm > form')[0]
            formNewComment.addEventListener('submit', function (event) {
                event.preventDefault()
                if (!formNewComment.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                formNewComment.classList.add('was-validated')
            }, false)
        }

        function setfilter (filter) {
            $.post('/setfilter', { 
                filter: filter
            }) 
            switchOnPage(currentPage)
        }
</script>
</div>