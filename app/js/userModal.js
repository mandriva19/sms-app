jQuery(document).ready(function($) {
    const modalOverlay = $("#unifiedModalOverlay");
    const modal = $("#unifiedModal");

    $(document).on("click", ".user-link", function(e) {
        e.preventDefault(); // Prevent default link behavior
        const userId = $(this).data("user-id"); // get user ID from data attribute
        modalOverlay.addClass("active"); // Show overlay

        $.ajax({
            url: authorModal.ajax_url,
            type: "POST",
            dataType: "json",
            data: {
                action: "get_user_modal_data",
                user_id: userId,
                _ajax_nonce: authorModal.nonce
            },
            beforeSend: function() {
                modal.find(".author_meta").html("<p>Loading...</p>");
                modalOverlay.show();
            },
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    modal.find(".author_meta").html(`
                        <img class="author_meta__img" src="${data.avatar}" alt="">
                        <small>${data.name}</small><br>
                        <small>Location: ${data.location}</small><br>
                        <small>Posts: ${data.posts}</small><br><br>
                        <p>Bio: ${data.bio}</p>
                        <a class="d-block bg-danger text-white text-center py-1 px-2" href="${data.profile}">
                            პროფილის ნახვა
                        </a>
                    `);
                } else {
                    modal.find(".author_meta").html(`<p>${response.data.message}</p>`);
                }
            },
            error: function() {
                modal.find(".author_meta").html("<p>Error loading data.</p>");
            }
        });
    });

    // Close modal on overlay click
    modalOverlay.on("click", function(e) {
        if (e.target.id === "unifiedModalOverlay") {
            modalOverlay.removeClass("active");
        }
    });
});
