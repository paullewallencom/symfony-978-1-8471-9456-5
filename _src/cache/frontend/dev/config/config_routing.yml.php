<?php
// auto-generated by sfRoutingConfigHandler
// date: 2009/09/04 14:28:23
return array(
'homepage' => new sfRoute('/', array (
  'module' => 'home',
  'action' => 'index',
), array (
), array (
)),
'menu' => new sfRequestRoute('/menu/:page', array (
  'module' => 'menu',
  'action' => 'index',
  'page' => 1,
), array (
  'page' => '\\d+',
  'sf_method' => 
  array (
    0 => 'get',
  ),
), array (
)),
'menu_item' => new sfPropelRoute('/menu/milkshake/:url_slug', array (
  'module' => 'menu',
  'action' => 'milkshake',
), array (
), array (
  'model' => 'MilkShake',
  'type' => 'object',
)),
'locations' => new sfRoute('/locations', array (
  'module' => 'location',
  'action' => 'index',
), array (
), array (
)),
'vacancies' => new sfRoute('/:sf_culture/vacancies', array (
  'module' => 'vacancies',
  'action' => 'index',
), array (
  '{sf_culture' => '(?:en_AU|en_GB|fr_FR)}',
), array (
)),
'best' => new sfRoute('/best', array (
  'module' => 'best',
  'action' => 'index',
), array (
), array (
)),
'default_index' => new sfRoute('/:module', array (
  'action' => 'index',
), array (
), array (
)),
'default' => new sfRoute('/:module/:action/*', array (
), array (
), array (
)),
);
