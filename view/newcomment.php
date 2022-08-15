<div class="modal" id="newCommentForm" tabindex="-1" aria-labelledby="newCommentFormLabel" aria-hidden="true">
    <form class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newCommentFormLabel">New comment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body position-relative py-4">
                <div class="pb-2">Name<span class="text-danger">*</span> <input type="text" name="user_name" required>
                    <div class="invalid-feedback">
                        Please choose a username.
                    </div>
                </div>
                <div class="pb-2">Text<span class="text-danger">*</span> </div>
                <textarea name="content" rows="10" cols=50 required></textarea>
                <div class="invalid-feedback">
                    Please choose a username.
                </div>
                <input type="hidden" name="post_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary button-submit" data-bs-focus="true" onclick="addNewComment()">Submit</button>
            </div>
        </div>
    </form>
</div>