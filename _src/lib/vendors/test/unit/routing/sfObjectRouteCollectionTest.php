<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

$t = new lime_test(14);

// ->__construct()
$t->diag('->__construct()');

try
{
  $collection = new sfObjectRouteCollection(array('name' => 'test'));
  $t->fail('->__construct() throws an exception if no "model" option is provided');
}
catch (InvalidArgumentException $e)
{
  $t->pass('->__construct() throws an exception if no "model" option is provided');
}

$collection = new sfObjectRouteCollection(array('name' => 'test', 'model' => 'TestModel'));
$options = $collection->getOptions();
$t->is($options['column'], 'id', '->__construct() defaults "column" option to "id"');
$t->is_deeply($options['requirements'], array('id' => '\d+'), '->__construct() defaults "requirements" for column to "\d+"');

$collection = new sfObjectRouteCollection(array('name' => 'test', 'model' => 'TestModel', 'column' => 'slug'));
$options = $collection->getOptions();
$t->is_deeply($options['requirements'], array('slug' => null), '->__construct() does not set a default requirement for custom columns');

// with_wildcard_routes
$t->diag('with_wildcard_routes');

$collection = new sfObjectRouteCollection(array(
  'name'                 => 'test',
  'model'                => 'TestModel',
  'with_wildcard_routes' => true,
));

$routes = $collection->getRoutes();
$t->isa_ok($routes['test_object'], 'sfObjectRoute', '->generateRoutes() generates a wildcard object route when "with_wildcard_routes" is true');
$t->isa_ok($routes['test_collection'], 'sfObjectRoute', '->generateRoutes() generates a wildcard collection route when "with_wildcard_routes" is true');

$url = $routes['test_object']->generate(array('id' => 123, 'action' => 'export'));
$t->isa_ok($routes['test_object']->matchesUrl($url, array('method' => 'get')), 'array', '->generateRoutes() creates a wildcard object route that matches a URL it generates');

$match = null;
foreach ($routes as $name => $route)
{
  if ($route->matchesUrl($url, array('method' => 'get')))
  {
    $match = $name;
    break;
  }
}
$t->is($match, 'test_object', '->generateRoutes() orders routes so URLs generated by the wildcard object route are matched by that route');

$url = $routes['test_collection']->generate(array('action' => 'export'));
$t->isa_ok($routes['test_collection']->matchesUrl($url, array('method' => 'post')), 'array', '->generateRoutes() creates a wildcard collection route that matches a URL it generates');

$match = null;
foreach ($routes as $name => $route)
{
  if ($route->matchesUrl($url, array('method' => 'post')))
  {
    $match = $name;
    break;
  }
}
$t->is($match, 'test_collection', '->generateRoutes() orders routes so URLs generated by the wildcard collection route are matched by that route');

// collection_actions
$t->diag('collection_actions');

$collection = new sfObjectRouteCollection(array(
  'name'                 => 'test',
  'model'                => 'TestModel',
  'with_wildcard_routes' => true,
  'collection_actions'   => array('export' => array('post')),
));

$routes = $collection->getRoutes();
$t->isa_ok($routes['test_export'], 'sfObjectRoute', '->generateRoutes() generates custom collection routes');

$url = $routes['test_export']->generate();
$t->isa_ok($routes['test_export']->matchesUrl($url, array('method' => 'post')), 'array', '->generateRoutes() creates a collection action route that matches a URL it generates');

$match = null;
foreach ($routes as $name => $route)
{
  if ($route->matchesUrl($url, array('method' => 'post')))
  {
    $match = $name;
    break;
  }
}
$t->is($match, 'test_export', '->generateRoutes() orders routes so URLs generated by the collection action route are matched by that route');

$url = $routes['test_collection']->generate(array('action' => 'export'));
$match = null;
foreach ($routes as $name => $route)
{
  if ($route->matchesUrl($url, array('method' => 'post')))
  {
    $match = $name;
    break;
  }
}
$t->is($match, 'test_collection', '->generateRoutes() orders routes so URLs generated by the wildcard collection and collection action routes do not conflict');
