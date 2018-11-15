<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use Salamek\MojeOlomouc\Enum\ArticleApproveStateEnum;
use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Model\Article;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\Tests\MojeOlomouc\BaseTest;

class ArticleTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param string $content
     * @param string $author
     * @param int $categoryId
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
        int $categoryId,
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
            $categoryId,
            $dateTimeAt
        );

        $this->assertEquals($title, $article->getTitle());
        $this->assertEquals($content, $article->getContent());
        $this->assertEquals($author, $article->getAuthor());
        $this->assertEquals($categoryId, $article->getCategoryId());
        $this->assertEquals($dateTimeAt, $article->getDateTimeAt());
        $this->assertEquals([], $article->getImages());
        $this->assertEquals(null, $article->getAttachmentUrl());
        $this->assertEquals(null, $article->getIsVisible());
        $this->assertEquals(null, $article->getIsImportant());
        $this->assertEquals(null, $article->getApproveState());
        $this->assertEquals(null, $article->getId());
        $this->assertInternalType('array', $article->toPrimitiveArray());


        $primitiveImages = [];
        foreach ($article->getImages() AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $primitiveArrayTest = [
            'title' => $title,
            'content' => $content,
            'author' => $author,
            'categoryId' => $categoryId,
            'dateTimeAt' => $dateTimeAt->format(DateTime::NOT_A_ISO8601),
            'images' => $primitiveImages,
        ];

        $this->assertEquals($primitiveArrayTest, $article->toPrimitiveArray());
    }


    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param string $content
     * @param string $author
     * @param int $categoryId
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
        int $categoryId,
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
            $categoryId,
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
        $this->assertEquals($categoryId, $article->getCategoryId());
        $this->assertEquals($dateTimeAt, $article->getDateTimeAt());
        $this->assertEquals($images, $article->getImages());
        $this->assertEquals($attachmentUrl, $article->getAttachmentUrl());
        $this->assertEquals($isVisible, $article->getIsVisible());
        $this->assertEquals($isImportant, $article->getIsImportant());
        $this->assertEquals($approveState, $article->getApproveState());
        $this->assertEquals($id, $article->getId());
        $this->assertInternalType('array', $article->toPrimitiveArray());
        $primitiveImages = [];

        foreach ($images AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $primitiveArrayTest = [
            'title' => $title,
            'content' => $content,
            'author' => $author,
            'categoryId' => $categoryId,
            'dateTimeAt' => $dateTimeAt->format(DateTime::NOT_A_ISO8601),
            'images' => $primitiveImages
        ];

        if (!is_null($attachmentUrl)) $primitiveArrayTest['attachmentUrl'] = $attachmentUrl;
        if (!is_null($isVisible)) $primitiveArrayTest['isVisible'] = $isVisible;
        if (!is_null($isImportant)) $primitiveArrayTest['isImportant'] = $isImportant;
        if (!is_null($approveState)) $primitiveArrayTest['approveState'] = $approveState;

        $this->assertEquals($primitiveArrayTest, $article->toPrimitiveArray());
    }

    /**
     * @test
     * @dataProvider provideInvalidConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param string $title
     * @param string $content
     * @param string $author
     * @param int $categoryId
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
        int $categoryId,
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
            $categoryId,
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
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param string $content
     * @param string $author
     * @param int $categoryId
     * @param \DateTimeInterface $dateTimeAt
     * @param array $images
     * @param string|null $attachmentUrl
     * @param bool $isVisible
     * @param bool $isImportant
     * @param int|null $approveState
     * @param int|null $id
     */
    public function createFromRequiredPrimitiveArrayShouldBeGood(
        string $title,
        string $content,
        string $author,
        int $categoryId,
        \DateTimeInterface $dateTimeAt,
        array $images = [],
        string $attachmentUrl = null,
        bool $isVisible = null,
        bool $isImportant = null,
        int $approveState = null,
        int $id = null
    )
    {
        $primitiveImages = [];
        foreach($images AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $article = Article::fromPrimitiveArray(
            [
                'title' => $title,
                'content' => $content,
                'author' => $author,
                'categoryId' => $categoryId,
                'dateTimeAt' => $dateTimeAt->format(DateTime::NOT_A_ISO8601),
                'images' => $primitiveImages,
            ]
        );

        $this->assertEquals($title, $article->getTitle());
        $this->assertEquals($content, $article->getContent());
        $this->assertEquals($author, $article->getAuthor());
        $this->assertEquals($categoryId, $article->getCategoryId());
        $this->assertEquals($dateTimeAt, $article->getDateTimeAt());
        $this->assertEquals($images, $article->getImages());
        $this->assertEquals(null, $article->getAttachmentUrl());
        $this->assertEquals(null, $article->getIsVisible());
        $this->assertEquals(null, $article->getIsImportant());
        $this->assertEquals(null, $article->getApproveState());
        $this->assertEquals(null, $article->getId());
    }

    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param string $content
     * @param string $author
     * @param int $categoryId
     * @param \DateTimeInterface $dateTimeAt
     * @param array $images
     * @param string|null $attachmentUrl
     * @param bool $isVisible
     * @param bool $isImportant
     * @param int|null $approveState
     * @param int|null $id
     */
    public function createFromOptionalPrimitiveArrayShouldBeGood(
        string $title,
        string $content,
        string $author,
        int $categoryId,
        \DateTimeInterface $dateTimeAt,
        array $images = [],
        string $attachmentUrl = null,
        bool $isVisible = null,
        bool $isImportant = null,
        int $approveState = null,
        int $id = null
    )
    {
        $primitiveImages = [];
        foreach($images AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $article = Article::fromPrimitiveArray(
            [
                'title' => $title,
                'content' => $content,
                'author' => $author,
                'categoryId' => $categoryId,
                'dateTimeAt' => $dateTimeAt->format(DateTime::NOT_A_ISO8601),
                'images' => $primitiveImages,
                'attachmentUrl' => $attachmentUrl,
                'isVisible' => $isVisible,
                'isImportant' => $isImportant,
                'approveState' => $approveState,
                'id' => $id
            ]
        );

        $this->assertEquals($title, $article->getTitle());
        $this->assertEquals($content, $article->getContent());
        $this->assertEquals($author, $article->getAuthor());
        $this->assertEquals($categoryId, $article->getCategoryId());
        $this->assertEquals($dateTimeAt, $article->getDateTimeAt());
        $this->assertEquals($images, $article->getImages());
        $this->assertEquals($attachmentUrl, $article->getAttachmentUrl());
        $this->assertEquals($isVisible, $article->getIsVisible());
        $this->assertEquals($isImportant, $article->getIsImportant());
        $this->assertEquals($approveState, $article->getApproveState());
        $this->assertEquals($id, $article->getId());
    }

    /**
     * @return array
     */
    public function provideInvalidConstructorParameters(): array
    {
        return [
            [str_repeat('title-'.mt_rand(), 128), 'content-'.mt_rand(), 'author-'.mt_rand(), mt_rand(), $this->getDateTime(), [], 'attachmentUrl-'.mt_rand(), true, false, null, null],
            ['title-'.mt_rand(), 'content-'.mt_rand(), str_repeat('author-'.mt_rand(), 128), mt_rand(), $this->getDateTime(), [], 'attachmentUrl-'.mt_rand(), true, false, null, null],
            ['title-'.mt_rand(), 'content-'.mt_rand(), 'author-'.mt_rand(), mt_rand(), $this->getDateTime(), ['notAEntityImage'], 'attachmentUrl-'.mt_rand(), true, false, null, null],
        ];
    }


    /**
     * @return array
     */
    public function provideValidConstructorParameters(): array
    {
        $image = new EntityImage('url');
        return [
            ['title-'.mt_rand(), 'content'.mt_rand(), 'author-'.mt_rand(), mt_rand(), $this->getDateTime(), [$image], 'attachmentUrl-'.mt_rand(), true, false, null, null],
            ['title-'.mt_rand(), 'content'.mt_rand(), 'author-'.mt_rand(), mt_rand(), $this->getDateTime(), [], null, false, true, ArticleApproveStateEnum::WAITING_FOR_DELETE, mt_rand()]
        ];
    }
}