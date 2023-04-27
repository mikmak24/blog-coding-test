<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Laravel</title>
      <!-- Fonts -->
      <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
      <link href="{{ asset('css/app.css') }}" rel="stylesheet">
      <style>
         body {
         font-family: 'Nunito', sans-serif;
         }
      </style>
   </head>
   <body class="antialiased">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5" id="mainNav">
         <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="index.html">Blog Coding Test</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
               <ul class="navbar-nav ms-auto py-4 py-lg-0">
                  <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="index.html">Home</a></li>
                  <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="about.html">About</a></li>
                  <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="post.html">Sample Post</a></li>
                  <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="contact.html">Contact</a></li>
               </ul>
            </div>
         </div>
      </nav>
      <div class="px-4 px-lg-5">
         @foreach ($posts as $post)
         <div class="container px-4 px-lg-5">
            <div class="col-md-10 col-lg-8 col-xl-7">
               <!-- Post preview-->
               <div class="post-preview">
                  <h1><b>{{ $post->title }}</b></h1>
                  <p class="post-meta">
                     Posted by
                     <a href="#!">{{ $post->posted_by }}</a>
                     on {{ $post->date_posted }}
                  </p>
                  <p>
                  <h2 class="post-title">{{ $post->content }}</h2>
                  </p>
                  <div class="card">
                     <h5 class="card-header"></h5>
                     <div id="comments-section" class="card-body">
                        @foreach ($post->comments as $comment)
                        <span>
                           <h5 class="card-title">{{ $comment->comment }} - {{$comment->user_name}} </h5>
                        </span>
                        <input type="number" hidden class="form-control comment-id" id="comment-id-{{ $comment->id }}" value="{{ $comment->id }}">
                        <input type="text" class="form-control reply"  placeholder="Enter Reply" name="pswd">
                        <ul class="replies comment-{{ $comment->id }}">
                           @foreach ($comment->replies as $reply)
                           <li>{{ $reply->comment_reply }}</li>
                           @endforeach
                        </ul>
                        <hr>
                        @endforeach
                     </div>
                  </div>
                  <br>
                  <h5>
                  Leave a Comment:
                  <div class="mb-3 mt-3">
                     <textarea class="form-control" id="comment" placeholder="Enter Comment" name="newcomment"></textarea>
                  </div>
                  <div class="mb-3">
                     <input type="text" class="form-control" id="name" placeholder="Enter Name" name="pswd">
                  </div>
                  <input type="number" hidden class="form-control" id="post_id" value='{{ $post->id }}'>
                  <button name="submit" class="btn btn-primary">Post Comment</button>
               </div>
               <!-- Divider-->
               <hr class="my-4" />
            </div>
         </div>
      </div>
      @endforeach
   </body>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>
      $(document).ready(function() {
          $('button[name="submit"]').click(function(e) {
              e.preventDefault(); // prevent the form from submitting
      
              var comment = $('#comment').val(); // get the value of the comment input
              var name = $('#name').val(); // get the value of the name input
              var post_id = $('#post_id').val();
      
              // do something with the comment and name values (e.g. send them to the server via AJAX)
               // send the form data to the server via AJAX
              $.ajax({
                  type: "POST",
                  url: "{{ route('blog.store') }}",
                  data: {
                      _token: "{{ csrf_token() }}",
                      comment: comment,
                      name: name,
                      post_id: post_id
                  },
                  success: function(data) {
                      var newComment = '<h5 class="card-title">' + comment + '</h5><ul></ul>';

                      $('#comments-section').append(newComment);
                  },
                  
              });

              $('#comment').val('');
              $('#name').val('');
          });
      
      
          $('.reply').keypress(function(event) {
    if (event.keyCode === 13) {
        var reply = $(this).val();
        var commentId = $(this).prev('.comment-id').val();
        var $replyInput = $(this); // Store reference to the input element
      
        $.ajax({
            type: "POST",
            url: "{{ route('blog.store.reply') }}",
            data: {
                _token: "{{ csrf_token() }}",
                comment_id: commentId,
                reply: reply,
            },
            success: function(data) {
                if(data == 'exceed'){
                    alert('The reply to this comment is already more than 3')
                } else {
                    var newReplyText = reply;
                    appendReplyToComment(commentId, newReplyText);
                }
                location.reload();
            }
        });
    }
});

function appendReplyToComment(commentId, replyText) {
    var $replies = $('.comment-' + commentId + ' .replies');
    $replies.append('<li>' + replyText + '</li>');
}


      });
   </script>
</html>