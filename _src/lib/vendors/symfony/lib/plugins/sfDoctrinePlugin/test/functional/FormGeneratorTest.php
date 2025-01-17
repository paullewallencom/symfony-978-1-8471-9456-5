<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app = 'backend';
$fixtures = 'fixtures';
require_once(dirname(__FILE__).'/../bootstrap/functional.php');

$t = new lime_test(22);

$t->diag("Test that these models don't generate forms or filters classes");
$noFormsOrFilters = array('UserGroup', 'UserPermission', 'GroupPermission');
foreach ($noFormsOrFilters as $model)
{
  $t->is(file_exists(sfConfig::get('sf_lib_dir').'/form/doctrine/'.$model.'Form.class.php'), false);
  $t->is(file_exists(sfConfig::get('sf_lib_dir').'/form/doctrine/base/Base'.$model.'Form.class.php'), false);
  $t->is(file_exists(sfConfig::get('sf_lib_dir').'/filter/doctrine/'.$model.'FormFilter.class.php'), false);
  $t->is(file_exists(sfConfig::get('sf_lib_dir').'/filter/doctrine/base/Base'.$model.'FormFilter.class.php'), false);
}

$t->diag('FormGeneratorTest model should generate forms but not filters');
$t->is(file_exists(sfConfig::get('sf_lib_dir').'/form/doctrine/FormGeneratorTestForm.class.php'), true);
$t->is(file_exists(sfConfig::get('sf_lib_dir').'/form/doctrine/base/BaseFormGeneratorTestForm.class.php'), true);

$t->is(file_exists(sfConfig::get('sf_lib_dir').'/filter/doctrine/FormGeneratorTestFormFilter.class.php'), false);
$t->is(file_exists(sfConfig::get('sf_lib_dir').'/filter/doctrine/base/BaseFormGeneratorTestFormFilter.class.php'), false);

$t->diag('FormGeneratorTest2 model should generator filters but not forms');
$t->is(file_exists(sfConfig::get('sf_lib_dir').'/form/doctrine/FormGeneratorTest2Form.class.php'), false);
$t->is(file_exists(sfConfig::get('sf_lib_dir').'/form/doctrine/base/BaseFormGeneratorTest2Form.class.php'), false);

$t->is(file_exists(sfConfig::get('sf_lib_dir').'/filter/doctrine/FormGeneratorTest2FormFilter.class.php'), true);
$t->is(file_exists(sfConfig::get('sf_lib_dir').'/filter/doctrine/base/BaseFormGeneratorTest2FormFilter.class.php'), true);

$t->diag('Check form genreator generates forms with correct inheritance');
$test = new AuthorInheritanceForm();
$t->is(is_subclass_of($test, 'AuthorForm'), true);

$test = new AuthorInheritanceFormFilter();
$t->is(is_subclass_of($test, 'AuthorFormFilter'), true);