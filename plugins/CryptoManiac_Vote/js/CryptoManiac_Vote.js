jQuery(document).ready(function($) {
    // AJAX request for voting
    $('.voting-button').on('click', function() {
        var button = $(this);
        var postId = button.data('post-id');
        var action = button.data('action');
        
        $.ajax({
            type: 'POST',
            url: voting_ajax_object.ajax_url,
            data: {
                action: 'process_vote',
                post_id: postId,
                vote_action: action,
                security: voting_ajax_object.security,
            },
            success: function(response) {
                // Handle success, update UI with new voting results
                if (response.success) {
                    var results = response.data.results;
                    $('#voting_front').html(
                        '<p>Thank you for your feedback.' +
                        '<div id="cmpositive">  ' + results.average_percentage + ' %</div>' +
                        '<div id="cmnegative">  ' + (100 - results.average_percentage) + ' %</div>' +
                        '</p>'
                    );
                } else {
                    // Handle error, display message, etc.
                    console.error('Error:', response.data.message);
                }
            },
            error: function(error) {
                // Handle error, display message, etc.
                console.error('Error:', error);
            }
        });
    });
});