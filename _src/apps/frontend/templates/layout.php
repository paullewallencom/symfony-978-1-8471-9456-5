<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
   <style type="text/css">
    body{margin:0; padding:0;font-family: Arial, Verdana, sans-serif;font-size: 11px;}
    a img,:link img,:visited img { border: none; color: #000000; }
  </style>
  </head>
  <body>
<div style="width: 786px; border: 1px solid #000000; margin: auto;">
<div style="height: 100px;">
    <div style="text-align: center"><h1>Top Banner</h1></div>
</div>

<div style="width: 192px; float: left; ">
    <div style="width: 100%; text-align: left;">
        <ul style="list-style-type:none;margin:0;padding:0;">
            <li style="margin:0;padding:0.25em 0.5em 0.25em 0.5em; width: 150px; border-bottom: 1px solid #000000; border-right: 1px solid #000000; ; border-top: 1px solid #000000; "><?php echo link_to('Home', '@homepage') ?></li>
          <li style="margin:0;padding:0.25em 0.5em 0.25em 0.5em; width: 150px; border-bottom: 1px solid #000000; border-right: 1px solid #000000; "><?php echo link_to('Menu', '@menu') ?></li>
          <li style="margin:0;padding:0.25em 0.5em 0.25em 0.5em; width: 150px; border-bottom: 1px solid #000000; border-right: 1px solid #000000;  "><?php echo link_to('Store Locations', '@locations') ?></li>
          <li style="margin:0;padding:0.25em 0.5em 0.25em 0.5em; width: 150px; border-bottom: 1px solid #000000; border-right: 1px solid #000000;  "><?php echo link_to('Vacancies', '@vacancies') ?></li>
          <li style="margin:0;padding:0.25em 0.5em 0.25em 0.5em; width: 150px; border-bottom: 1px solid #000000; border-right: 1px solid #000000;  "><?php echo link_to('Newsletter', '@signup') ?></li> 
          <li style="margin:0;padding:0.25em 0.5em 0.25em 0.5em; width: 150px; border-bottom: 1px solid #000000; border-right: 1px solid #000000;  "><?php echo link_to('Best Flavours', '@best') ?></li> 
        </ul>
    </div>
</div>

<div style="margin-left: 208px">
    <div style="min-height: 100px"><?php echo $sf_content ?></div>
  </div>

<div style="text-align: center"><h1>Footer</h1></div>
<div style="clear:both"></div>
</div>
</body>
</html>
