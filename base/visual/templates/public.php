<!DOCTYPE html>
<html lang="<?php echo Language::active();?>">
<head>

    <meta charset="utf-8">
    <meta name="description" content="<?php echo $metaDescription;?>"/>
    <meta name="keywords" content="<?php echo $metaKeywords;?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php echo Parameter::code('google_webmasters');?>

    <meta property="og:title" content="<?php echo $title;?>" />
    <meta property="og:description" content="<?php echo $metaDescription;?>" />
    <meta property="og:url" content="<?php echo $metaUrl;?>" />
    <?php echo $metaImage;?>

    <link rel="shortcut icon" href="<?php echo ASTERION_BASE_URL;?>visual/img/favicon.ico"/>
    <link rel="canonical" href="<?php echo $metaUrl;?>" />

    <title><?php echo $title;?></title>

    <link href="<?php echo ASTERION_BASE_URL;?>visual/css/stylesheets/public.css?v=17" rel="stylesheet" type="text/css" />

    <?php echo Navigation_Ui::analytics();?>
    <?php echo $head;?>

</head>
<body>

    <?php echo $content;?>

</body>
</html>
