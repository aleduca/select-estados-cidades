<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->e($title); ?></title>
    <link rel="stylesheet" type="text/css" href="/assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">

</head>
<body>
      <div id="header">
            <?php $this->insert('partials/header') ?>
      </div>
      <div class="container">
            <?=$this->section('content')?>
      </div>

      <?=$this->section('scripts')?>

      <script src="./app.js"></script>
</body>
</html>
