<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use Salamek\MojeOlomouc\Enum\ArticleApproveStateEnum;
use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Model\Article;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\MojeOlomouc\Model\Identifier;
use Salamek\Tests\MojeOlomouc\BaseTest;

class ArticleTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param string $content
     * @param string $author
     * @param Identifier $category
     * @param \DateTimeInterface $dateTimeAt
     * @param array $images
     * @param string|null $attachmentUrl
     * @param bool $isVisible
     * @param bool $isImportant
     * @param int|null $approveState
     * @param int|null $id
     */
    public function createRequiredShouldBeGoodTest(
        string $title,
        string $content,
        string $author,
        Identifier $category,
        \DateTimeInterface $dateTimeAt,
        array $images = [],
        string $attachmentUrl = null,
        bool $isVisible = null,
        bool $isImportant = null,
        int $approveState = null,
        int $id = null
    )
    {
        $article = new Article(
            $title,
            $content,
            $author,
            $category,
            $dateTimeAt
        );

        $this->assertEquals($title, $article->getTitle());
        $this->assertEquals($content, $article->getContent());
        $this->assertEquals($author, $article->getAuthor());
        $this->assertEquals($category, $article->getCategory());
        $this->assertEquals($dateTimeAt, $article->getDateTimeAt());
        $this->assertEquals([], $article->getImages());
        $this->assertEquals(null, $article->getAttachmentUrl());
        $this->assertEquals(null, $article->getIsVisible());
        $this->assertEquals(null, $article->getIsImportant());
        $this->assertEquals(null, $article->getApproveState());
        $this->assertEquals(null, $article->getEntityIdentifier());
    }


    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param string $content
     * @param string $author
     * @param Identifier $category
     * @param \DateTimeInterface $dateTimeAt
     * @param array $images
     * @param string|null $attachmentUrl
     * @param bool $isVisible
     * @param bool $isImportant
     * @param int|null $approveState
     * @param int|null $id
     */
    public function createOptionalShouldBeGoodTest(
        string $title,
        string $content,
        string $author,
        Identifier $category,
        \DateTimeInterface $dateTimeAt,
        array $images = [],
        string $attachmentUrl = null,
        bool $isVisible = null,
        bool $isImportant = null,
        int $approveState = null,
        int $id = null
    )
    {
        $article = new Article(
            $title,
            $content,
            $author,
            $category,
            $dateTimeAt,
            $images,
            $attachmentUrl,
            $isVisible,
            $isImportant,
            $approveState,
            $id
        );

        $this->assertEquals($title, $article->getTitle());
        $this->assertEquals($content, $article->getContent());
        $this->assertEquals($author, $article->getAuthor());
        $this->assertEquals($category, $article->getCategory());
        $this->assertEquals($dateTimeAt, $article->getDateTimeAt());
        $this->assertEquals($images, $article->getImages());
        $this->assertEquals($attachmentUrl, $article->getAttachmentUrl());
        $this->assertEquals($isVisible, $article->getIsVisible());
        $this->assertEquals($isImportant, $article->getIsImportant());
        $this->assertEquals($approveState, $article->getApproveState());
        $this->assertEquals($id, $article->getEntityIdentifier());

    }

    /**
     * @test
     * @dataProvider provideInvalidConstructorParameters
     * @expectedException \Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param string $title
     * @param string $content
     * @param string $author
     * @param Identifier $category
     * @param \DateTimeInterface $dateTimeAt
     * @param array $images
     * @param string|null $attachmentUrl
     * @param bool $isVisible
     * @param bool $isImportant
     * @param int|null $approveState
     * @param int|null $id
     */
    public function createOptionalShouldFailOnBadData(
        string $title,
        string $content,
        string $author,
        Identifier $category,
        \DateTimeInterface $dateTimeAt,
        array $images = [],
        string $attachmentUrl = null,
        bool $isVisible = null,
        bool $isImportant = null,
        int $approveState = null,
        int $id = null
    )
    {
        new Article(
            $title,
            $content,
            $author,
            $category,
            $dateTimeAt,
            $images,
            $attachmentUrl,
            $isVisible,
            $isImportant,
            $approveState,
            $id
        );
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideInvalidConstructorParameters(): array
    {
        return [
            [str_repeat('title-'.mt_rand(), 128), 'content-'.mt_rand(), 'author-'.mt_rand(), new Identifier(mt_rand()), $this->getDateTime(), [], 'attachmentUrl-'.mt_rand(), true, false, null, null],
            ['title-'.mt_rand(), 'content-'.mt_rand(), str_repeat('author-'.mt_rand(), 128), new Identifier(mt_rand()), $this->getDateTime(), [], 'attachmentUrl-'.mt_rand(), true, false, null, null],
            ['title-'.mt_rand(), 'content-'.mt_rand(), 'author-'.mt_rand(), new Identifier(mt_rand()), $this->getDateTime(), ['notAEntityImage'], 'attachmentUrl-'.mt_rand(), true, false, null, null],
        ];
    }


    /**
     * @return array
     * @throws \Exception
     */
    public function provideValidConstructorParameters(): array
    {
        $image = new EntityImage('url');
        return [
            ['title-'.mt_rand(), 'content'.mt_rand(), 'author-'.mt_rand(), new Identifier(mt_rand()), $this->getDateTime(), [$image], 'attachmentUrl-'.mt_rand(), true, false, null, null],
            ['title-'.mt_rand(), 'content'.mt_rand(), 'author-'.mt_rand(), new Identifier(mt_rand()), $this->getDateTime(), [], null, false, true, ArticleApproveStateEnum::WAITING_FOR_DELETE, mt_rand()]
        ];
    }
}