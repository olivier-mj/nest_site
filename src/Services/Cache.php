<?php
namespace App\Services;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

class Cache extends TagAwareAdapter implements CacheInterface, TagAwareAdapterInterface
{

}