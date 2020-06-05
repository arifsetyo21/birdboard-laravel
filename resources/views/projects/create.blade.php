<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.2/css/bulma.min.css">
   <title>Document</title>
</head>

<body>
   <div class="container" style="padding-top: 40px">

      <h1 class="heading is-1">Create a project</h1>

      <form method="POST" action="/projects">
         @csrf
         <div class="field">
            <label class="label" for="title">Title</label>
            <div class="control">
               <input type="text" class="input" name="title" placeholder="Title">
            </div>
         </div>

         <div class="field">
            <label class="label" for="description">Description</label>
            <div class="control">
               <textarea name="description" class="textarea"></textarea>
            </div>
         </div>

         <div class="field">
            <div class="control">
               <button type="submit" class="button is-link">Create Project</button>
            </div>
         </div>
      </form>
   </div>
</body>

</html>