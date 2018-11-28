<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Hydrator;


use Salamek\MojeOlomouc\Enum\ArticleApproveStateEnum;
use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Hydrator\IArticle;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\Tests\MojeOlomouc\BaseTest;

class ArticleTest extends BaseTest
{
    /** @var IArticle */
    private $hydrator;

    /** @var \Salamek\MojeOlomouc\Hydrator\IEntityImage */
    private $entityImageHydrator;

    public function setUp()
    {
        parent::setUp();

        $this->hydrator = $this->getHydrator(\Salamek\MojeOlomouc\Hydrator\IArticle::class);
        $this->entityImageHydrator = $this->getHydrator(\Salamek\MojeOlomouc\Hydrator\IEntityImage::class);
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
            $primitiveImages[] = $this->entityImageHydrator->toPrimitiveArray($image);
        }
        $article = $this->hydrator->fromPrimitiveArray(
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
        $this->assertEquals(null, $article->getEntityIdentifier());
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
            $primitiveImages[] = $this->entityImageHydrator->toPrimitiveArray($image);
        }
        $article = $this->hydrator->fromPrimitiveArray(
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
        $this->assertEquals($id, $article->getEntityIdentifier());
    }


    /**
     * @return array
     * @throws \Exception
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