<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- bootstrap -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- animations -->
       <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
  <!-- font -->
   <!-- <link rel="stylesheet" href="//cdn.web-fonts.ge/fonts/archyedt-bold/css/archyedt-bold.min.css"> -->
    <?php wp_head(); ?>
  
    <a class="bg-warning text-dark px-2 py-3" href="<?php echo bloginfo( 'url' );?>">home</a>
</head>
<body <?php body_class(); ?>>
