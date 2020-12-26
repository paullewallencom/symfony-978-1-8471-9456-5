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

      #top_menu {
        padding: 10px 0;
      }

      #top_menu a {
        font-family: arial, helvetica, clean, sans-serif;
        text-decoration: none;
        font-size: 14px;
      }

      #top_menu a:hover {
        text-decoration: underline;
      }

    </style>
  </head>
  <body>
    <?php if ($sf_user->isAuthenticated()): ?>
    <div id="top_menu">
      <?php echo link_to('Store Locations', '@store_location') ?> |
      <?php echo link_to('Vacancies', '@vacancy') ?> |
      <?php if ($sf_user->hasCredential('admin')): ?>
        <?php echo link_to('Users', '@sf_guard_user') ?> |
        <?php echo link_to('Groups', '@sf_guard_group') ?> |
        <?php echo link_to('Permissions', '@sf_guard_permission') ?> |
      <?php endif; ?>
      <?php echo link_to('Logout', '@sf_guard_signout') ?>
    </div>
    <?php endif; ?>

    <?php echo $sf_content ?>
  </body>
</html>
