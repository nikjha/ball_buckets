$(document).ready(function() {
    // Submit Bucket Form using Ajax
    $('#bucket-form').submit(function(event) {
        event.preventDefault();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/buckets',
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                alert(response.message);
                // Clear the form or update the buckets list
            },
            error: function(error) {
                alert('Error occurred while saving bucket.');
            }
        });
    });

    // Submit Ball Form using Ajax
    $('#ball-form').submit(function(event) {
        event.preventDefault();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/balls',
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                alert(response.message);
                // Clear the form or update the balls list
            },
            error: function(error) {
                alert('Error occurred while saving ball.');
            }
        });
    });

    // Submit Place Balls Form using Ajax
    $('#place-balls-form').submit(function(event) {
        event.preventDefault();
        alert("Fuck");
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/buckets/place-balls',
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                alert(response.message);
                // Update the buckets list or handle placed balls response
                // Call updateBucketsList(response.buckets) to update the list
            },
            error: function(error) {
                alert('Error occurred while placing balls in the bucket.');
            }
        });
    });
});
