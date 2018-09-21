<?php

return array (
  'autoload' => false,
  'hooks' => 
  array (
    'addon_after_upgrade' => 
    array (
      0 => 'cms',
    ),
  ),
  'route' => 
  array (
    '/$' => 'cms/index/index',
    '/c/[:diyname]' => 'cms/channel/index',
    '/t/[:name]' => 'cms/tags/index',
    '/a/[:diyname]' => 'cms/archives/index',
    '/p/[:diyname]' => 'cms/page/index',
    '/s' => 'cms/search/index',
    '/example$' => 'example/index/index',
    '/example/d/[:name]' => 'example/demo/index',
    '/example/d1/[:name]' => 'example/demo/demo1',
    '/example/d2/[:name]' => 'example/demo/demo2',
  ),
);