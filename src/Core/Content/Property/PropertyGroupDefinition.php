<?php declare(strict_types=1);

namespace Shopware\Core\Content\Property;

use Shopware\Core\Content\Property\Aggregate\PropertyGroupOption\PropertyGroupOptionDefinition;
use Shopware\Core\Content\Property\Aggregate\PropertyGroupTranslation\PropertyGroupTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Write\EntityExistence;

class PropertyGroupDefinition extends EntityDefinition
{
    public const DISPLAY_TYPE_TEXT = 'text';
    public const DISPLAY_TYPE_IMAGE = 'image';
    public const DISPLAY_TYPE_COLOR = 'color';

    public const SORTING_TYPE_NUMERIC = 'numeric';
    public const SORTING_TYPE_ALPHANUMERIC = 'alphanumeric';
    public const SORTING_TYPE_POSITION = 'position';

    public static function getEntityName(): string
    {
        return 'property_group';
    }

    public static function getCollectionClass(): string
    {
        return PropertyGroupCollection::class;
    }

    public static function getEntityClass(): string
    {
        return PropertyGroupEntity::class;
    }

    public static function getDefaults(EntityExistence $existence): array
    {
        $defaults = parent::getDefaults($existence);

        $defaults['displayType'] = self::DISPLAY_TYPE_TEXT;
        $defaults['sortingType'] = self::SORTING_TYPE_ALPHANUMERIC;

        return $defaults;
    }

    protected static function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new TranslatedField('name'),
            new TranslatedField('description'),
            (new StringField('display_type', 'displayType'))->setFlags(new Required()),
            (new StringField('sorting_type', 'sortingType'))->setFlags(new Required()),
            new TranslatedField('attributes'),
            new CreatedAtField(),
            new UpdatedAtField(),
            (new OneToManyAssociationField('options', PropertyGroupOptionDefinition::class, 'property_group_id', 'id'))->addFlags(new CascadeDelete(), new SearchRanking(SearchRanking::ASSOCIATION_SEARCH_RANKING)),
            (new TranslationsAssociationField(PropertyGroupTranslationDefinition::class, 'property_group_id'))->addFlags(new Required(), new CascadeDelete()),
        ]);
    }
}
