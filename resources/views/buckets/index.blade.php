<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Buckets and Balls</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid mt-5">
        <div class="row">
            <!-- Bucket Form Column -->
            <div class="col-md-3">
                <h2>Bucket Form</h2>
                <form id="bucket-form">
                    @csrf
                    <div class="form-group">
                        <label for="bucket-name">Bucket Name</label>
                        <input type="text" class="form-control" id="bucket-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="bucket-volume">Volume (in inches)</label>
                        <input type="text" class="form-control" id="bucket-volume" name="volume" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Bucket</button>
                </form>
            </div>
            <div class="col-md-3">
            	<h2>Ball Form</h2>
                <form id="ball-form">
                	<div class="form-group">
                		<label for="bucket-name">Ball Color</label>
                        <input type="text" class="form-control" id="bucket-name" name="color" required>
                    </div>
                    <div class="form-group">
                        <label for="bucket-volume">Volume (in inches)</label>
                        <input type="text" class="form-control" id="bucket-volume" name="volume" required>
                    </div>
                    <div class="form-group">
                        <label for="bucket-volume">Balls Quanity</label>
                        <input type="text" class="form-control" id="bucket-volume" name="qty" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Bucket</button>
                </form>
            </div>
            
            <!-- Place Balls Form Column -->
           <div class="col-md-3">
                <h2>Bucket Available</h2>
                <div id="buckets-list">
                    @foreach ($buckets as $bucket)
                    <p>Bucket: {{ $bucket->name }}</p>
                    <p>Size: {{ $bucket->volume }}</p>
                
                    @endforeach

                </div>
                <h2>Balls Suggested</h2>
                <div id="buckets-list">
                    @foreach ($suggestions as $suggestion)
                        <p>Ball Size [{{ $suggestion['volume'] }}] of {{ $suggestion['color'] }} {{ $suggestion['quantity'] }} quantities.</p>
                        
                    @endforeach
                </div>

            </div>
            <div class="col-md-3">
                <h2>Balls Available</h2>
                <form id="place-balls-form">
                    @csrf
                     <div class="form-group">
                        <label for="bucket-select">Select Bucket</label>
                        <select class="form-control" id="bucket-select" name="bucket" required>
                            @foreach ($buckets as $bucket)
                                <option value="{{ $bucket->id }}" data-volume="{{ $bucket->volume }}">{{ $bucket->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @foreach ($balls as $ball)
                    <div class="form-group">
                        <label for="balls-name">{{$ball->color}}:
                        <input type="text" class="form-control" id="quantity" name="qty" value="{{$ball->qty}}" required>
                        </label>
                    </div>
                    @endforeach
                    

                    <!-- <button type="submit" class="btn btn-primary">Place Balls in Bucket</button> -->
                </form>
            </div>

        </div>

        <!-- Results Row -->
        
        <div class="row mt-4">
            
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
    // Submit Bucket Form using Ajax
    $('#bucket-form').submit(function(event) {
        event.preventDefault();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/buckets_store',
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
            },
            error: function(error) {
                alert('Error occurred while placing balls in the bucket.');
            }
        });
    });
});

    </script>
</body>

</html>