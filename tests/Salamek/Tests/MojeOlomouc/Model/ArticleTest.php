<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use Salamek\MojeOlomouc\Exception\ArticleApproveStateEnum;
use Salamek\MojeOlomouc\Model\Article;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\Tests\MojeOlomouc\BaseTest;

class ArticleTest extends BaseTest
{
    /**
     * @test
     */
    public function createRequiredShouldBeGoodTest()
    {
        $dateTime = new \DateTime();
        $article = new Article(
            'title',
            'content',
            'author',
            1,
            $dateTime
        );

        $this->assertEquals('title', $article->getTitle());
        $this->assertEquals('content', $article->getContent());
        $this->assertEquals('author', $article->getAuthor());
        $this->assertEquals(1, $article->getCategoryId());
        $this->assertEquals($dateTime, $article->getDateTimeAt());
        $this->assertEquals([], $article->getImages());
        $this->assertEquals(null, $article->getAttachmentUrl());
        $this->assertEquals(true, $article->getIsVisible());
        $this->assertEquals(false, $article->getIsImportant());
        $this->assertEquals(null, $article->getApproveState());
        $this->assertEquals(null, $article->getId());
        $this->assertInternalType('array', $article->toPrimitiveArray());
        //@TODO check toPrimitiveArray output
        //@TODO add data source
    }


    /**
     * @test
     */
    public function createOptionalShouldBeGoodTest()
    {
        $dateTime = new \DateTime();
        $id = mt_rand();
        $image = new EntityImage('url');
        $article = new Article(
            'title',
            'content',
            'author',
            1,
            $dateTime,
            [$image],
            'url',
            false,
            true,
            ArticleApproveStateEnum::WAITING_FOR_ADD,
            $id
        );

        $this->assertEquals('title', $article->getTitle());
        $this->assertEquals('content', $article->getContent());
        $this->assertEquals('author', $article->getAuthor());
        $this->assertEquals(1, $article->getCategoryId());
        $this->assertEquals($dateTime, $article->getDateTimeAt());
        $this->assertEquals([$image], $article->getImages());
        $this->assertEquals('url', $article->getAttachmentUrl());
        $this->assertEquals(false, $article->getIsVisible());
        $this->assertEquals(true, $article->getIsImportant());
        $this->assertEquals(ArticleApproveStateEnum::WAITING_FOR_ADD, $article->getApproveState());
        $this->assertEquals($id, $article->getId());
        $this->assertInternalType('array', $article->toPrimitiveArray());
        //@TODO check toPrimitiveArray output
        //@TODO add data source
    }
}