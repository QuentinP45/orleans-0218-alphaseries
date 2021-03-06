<?php
/**
 * Created by PhpStorm.
 * User: aragorn
 * Date: 18/04/18
 * Time: 13:23
 */

namespace Model;

class SeasonManager extends AbstractManager
{
    const TABLE = 'season';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param array $data
     * @return string|void
     * @throws \Exception
     */
    public function insert(array $data)
    {
        $idSerie = $data[ 'idSerie' ];
        $nb = $data[ 'nbSeasons' ];
        if ($this->checkSeasonExist($nb, $idSerie)) {
            throw new \Exception('La saison existe déjà');
        } else {
            $query = "INSERT INTO $this->table (numberSeason, idserie) VALUES (:numb, :idSerie)";
            $statement = $this->pdoConnection->prepare($query);
            $statement->bindValue('numb', $nb);
            $statement->bindValue('idSerie', $idSerie);
            $statement->execute();
        }
    }

    public function checkSeasonExist(int $nb, int $idSerie): bool
    {
        $verif = "SELECT numberSeason FROM $this->table WHERE numberSeason = :number_season AND idserie = :idSerie";
        $result = $this->pdoConnection->prepare($verif);
        $result->setFetchMode(\PDO::FETCH_CLASS, $this->className);
        $result->bindValue('number_season', $nb, \PDO::PARAM_INT);
        $result->bindValue('idSerie', $idSerie, \PDO::PARAM_INT);
        $result->execute();
        $res = $result->fetchAll();
        $count = count($res);

        return $count;
    }
  
    public function selectSeason(int $idSerie)
    {
        $query = "SELECT id, numberSeason FROM $this->table WHERE idserie = :idSerie ORDER BY numberSeason ASC";
        $result = $this->pdoConnection->prepare($query);
        $result->setFetchMode(\PDO::FETCH_CLASS, $this->className);
        $result->bindValue('idSerie', $idSerie, \PDO::PARAM_INT);
        $result->execute();
        $res = $result->fetchAll();
        return $res;
    }
}
