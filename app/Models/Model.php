<?php

namespace App\Models;

use App\Facades\Db;
use Database\Orm\Mysql;
use Exception;

class Model
{
    /**
     * the table's name
     * @var string $table
     **/
    protected $table;
    /**
     * the table's primary key
     * @var string $primaryKey
     **/
    protected $primaryKey;
    /**
     * when a Model object is created or filled (in find() method)
     * we will keep its first (original) properties values to find out which of
     * them have been changed  when the save() method is called
     * @var array $originalAttributes
     **/
    protected $originalAttributes;
    /**
     * a shortcut to keep the properties that are used to keep data in the database
     * @var array $attributes
     **/
    protected $attributes;
    /**
     * the id property in a model represents EmployeeID in the database, we should keep this mapping for every conversion
     * @var array $attributesMap
     **/
    protected $attributesMap;

    public function __construct()
    {
        $this->attributes = array_keys($this->attributesMap);
        $this->setOriginal();
    }

    /**
     * provides a way to call some methods statically by adding a (_) at the first of methods' names
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        $allowedMethods = ["find", "convertRawDataToModelObject"];
        $methodName = str_replace("_", "", $name);
        // limit the allowed methods
        if (!in_array($methodName, $allowedMethods)) {
            throw new Exception("method not found");
        }
        return (new static())->{$methodName}(...$arguments);
    }

    /**
     * keep the original attributes
     */
    protected function setOriginal()
    {
        foreach ($this->attributes as $attribute) {
            $this->originalAttributes[$attribute] = $this->$attribute;
        }
    }

    /**
     * this method determines that an attribute has been changed after the model object was created or filled (by find())
     * @param string $attribute
     * @return bool
     */
    protected function isDirty(string $attribute): bool
    {
        if (!in_array($attribute, $this->attributes)) {
            return false;
        }
        return $this->originalAttributes[$attribute] != $this->$attribute;
    }

    /**
     * returns the model object filled by the database data
     * @param int $id the primary key
     * @return $this
     */
    public function find(int $id)
    {
        $rawData = Db::first($this->table, [
            $this->attributesMap[$this->primaryKey] => $id,
        ]);
        return $this->convertRawDataToModelObject($rawData);
    }

    /**
     * converts raw database data to the model object
     * @param array $rawData
     * @return $this
     */
    public function convertRawDataToModelObject(array $rawData): Model
    {
        $obj = new static();
        $flip = array_flip($this->attributesMap);
        foreach ($rawData as $key => $value) {
            $attribute = $flip[$key];
            $obj->{$attribute} = $value;
            // set the original attributes
            $obj->originalAttributes[$attribute] = $value;
        }
        return $obj;
    }

    /**
     * save data in the database
     * @return mixed
     */
    public function save()
    {
        // let's find the changed attributes to just update them
        $changedAttributes = [];
        foreach ($this->attributes as $attribute) {
            if ($this->primaryKey == $attribute) {
                continue;
            }
            if ($this->isDirty($attribute)) {
                $changedAttributes[$this->attributesMap[$attribute]] =
                    $this->$attribute;
            }
        }
        // if the primaryKey is null it means that the object is fresh
        // and it has not been got from the database
        // so the operation must be insert
        if (!is_null($this->{$this->primaryKey})) {
            if (empty($changedAttributes)) {
                return;
            }
            return Db::update($this->table, $changedAttributes, [
                $this->attributesMap[$this->primaryKey] =>
                    $this->{$this->primaryKey},
            ]);
        }
        return Db::insert($this->table, $changedAttributes);
    }
}
