<?php
namespace App\Managers\Eloquent\Criteria;

use App\Managers\Contracts\Criteria\CriterionInterface;

/**
 * Class WhereIsOrNotNull
 * @package App\Managers\Eloquent\Criteria
 */
class WhereIsOrNotNull implements CriterionInterface
{
    /**
     * @var string
     */
    protected $column;
    /**
     * @var bool
     */
    protected $checkIsNull = true;

    /**
     * @param string $column
     * @param bool $checkIsNull
     */
    public function __construct(string $column, bool $checkIsNull = true)
    {
        $this->column = $column;
        $this->checkIsNull = $checkIsNull;
    }

    /**
     * @inheritdoc
     */
    public function apply($entity)
    {
        if ($this->checkIsNull) {
            return $entity->whereNull($this->column);
        }
        return $entity->whereNotNull($this->column);
    }
}
