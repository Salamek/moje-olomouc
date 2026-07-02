<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use Salamek\MojeOlomouc\Enum\ArticleApproveStateEnum;
use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Model\Article;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\MojeOlomouc\Model\Identifier;
use Salamek\Tests\MojeOlomouc\BaseTest;

class ArticleTest extends BaseTest
{
    /**
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
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createRequiredShouldBeGoodTest(
        string $title,
        string $content,
        string $author,
        Identifier $category,
        \DateTimeInterface $dateTimeAt,
        array $images = [],
        ?string $attachmentUrl = null,
        ?bool $isVisible = null,
        ?bool $isImportant = null,
        ?int $approveState = null,
        ?int $id = null
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
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createOptionalShouldBeGoodTest(
        string $title,
        string $content,
        string $author,
        Identifier $category,
        \DateTimeInterface $dateTimeAt,
        array $images = [],
        ?string $attachmentUrl = null,
        ?bool $isVisible = null,
        ?bool $isImportant = null,
        ?int $approveState = null,
        ?int $id = null
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
#[Test]
#[DataProvider('provideInvalidConstructorParameters')]

    public function createOptionalShouldFailOnBadData(
        string $title,
        string $content,
        string $author,
        Identifier $category,
        \DateTimeInterface $dateTimeAt,
        array $images = [],
        ?string $attachmentUrl = null,
        ?bool $isVisible = null,
        ?bool $isImportant = null,
        ?int $approveState = null,
        ?int $id = null
    )
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
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

    public static function provideInvalidConstructorParameters(): array
    {
        return [
            [str_repeat('title-'.mt_rand(), 128), 'content-'.mt_rand(), 'author-'.mt_rand(), new Identifier(mt_rand()), self::getDateTime(), [], 'attachmentUrl-'.mt_rand(), true, false, null, null],
            ['title-'.mt_rand(), 'content-'.mt_rand(), str_repeat('author-'.mt_rand(), 128), new Identifier(mt_rand()), self::getDateTime(), [], 'attachmentUrl-'.mt_rand(), true, false, null, null],
            ['title-'.mt_rand(), 'content-'.mt_rand(), 'author-'.mt_rand(), new Identifier(mt_rand()), self::getDateTime(), ['notAEntityImage'], 'attachmentUrl-'.mt_rand(), true, false, null, null],
        ];
    }


    /**
     * @return array
     * @throws \Exception
     */

    public static function provideValidConstructorParameters(): array
    {
        $image = new EntityImage('url');
        return [
            ['title-'.mt_rand(), 'content'.mt_rand(), 'author-'.mt_rand(), new Identifier(mt_rand()), self::getDateTime(), [$image], 'attachmentUrl-'.mt_rand(), true, false, null, null],
            ['title-'.mt_rand(), 'content'.mt_rand(), 'author-'.mt_rand(), new Identifier(mt_rand()), self::getDateTime(), [], null, false, true, ArticleApproveStateEnum::WAITING_FOR_DELETE, mt_rand()]
        ];
    }
}