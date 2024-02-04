<?php
declare(strict_types=1);

/**
 *
 * @category   Pimcore
 * @package    Extendmate
 * @subpackage LoginTracker
 *
 * @author     Faiyaz Alam
 *
 * @link       https://github.com/faiyazalam
 */

namespace Extendmate\Pimcore\LoginTracker\Model\LoginAttempt;

use Extendmate\Pimcore\LoginTracker\ExtendmateLoginTrackerBundle;
use Pimcore\Model\Dao\AbstractDao;
use Pimcore\Model\Exception\NotFoundException;

class Dao extends AbstractDao
{
    /**
     * get record by id
     *
     * @throws NotFoundException
     */
    public function getById(?int $id = null): void
    {
        if ($id !== null) {
            $this->model->setId($id);
        }

        $data = $this->db->fetchAssociative('SELECT * FROM ' . ExtendmateLoginTrackerBundle::TABLE_LOGIN_ATTEMPTS . ' WHERE id = ?', [$this->model->getId()]);

        if (!$data) {
            throw new NotFoundException('LoginAttempt with the ID ' . $this->model->getId() . " doesn't exists");
        }

        $this->assignVariablesToModel($data);
    }

    public function save(): void
    {
        $vars = get_object_vars($this->model);

        $buffer = [];

        $validColumns = $this->getValidTableColumns(ExtendmateLoginTrackerBundle::TABLE_LOGIN_ATTEMPTS);

        if (count($vars)) {
            foreach ($vars as $k => $v) {
                if (!in_array($k, $validColumns)) {
                    continue;
                }

                $getter = 'get' . ucfirst($k);

                if (!is_callable([$this->model, $getter])) {

                    continue;
                }

                $value = $this->model->$getter();

                if (is_bool($value)) {
                    $value = (int)$value;
                }

                $buffer[$k] = $value;
            }
        }

        if ($this->model->getId() !== null) {
            $this->db->update(ExtendmateLoginTrackerBundle::TABLE_LOGIN_ATTEMPTS, $buffer, ['id' => $this->model->getId()]);

            return;
        }

        $this->db->insert(ExtendmateLoginTrackerBundle::TABLE_LOGIN_ATTEMPTS, $buffer);
        $this->model->setId((int)$this->db->lastInsertId());
    }

    public function delete(): void
    {
        $this->db->delete(ExtendmateLoginTrackerBundle::TABLE_LOGIN_ATTEMPTS, ['id' => $this->model->getId()]);
    }
}
