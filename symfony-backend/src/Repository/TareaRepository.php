<?php

namespace App\Repository;

use App\Entity\Tarea;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tarea>
 */
class TareaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tarea::class);
    }

    /**
     * Devuelve todas las tareas de un usuario ordenadas de la más reciente a la más antigua.
     *
     * @return Tarea[]
     */
    public function findByUsuarioOrdenadas(int $usuarioId): array
    {
        $qb = $this->createQueryBuilder('t'); // creamos el QueryBuilder con alias t

        return $qb
            ->andWhere('t.usuario = :usuarioId')    // filtramos por el usuario indicado
            ->setParameter('usuarioId', $usuarioId) // enlazamos el valor del parámetro
            ->orderBy('t.fechaCreacion', 'DESC')    // ordenamos por fecha de creación descendente
            ->getQuery()
            ->getResult();
    }
    /**
     * Busca tareas según filtros específicos.
     *
     * @return Tarea[]
     */
    public function buscarPorFiltros(int $usuarioId, array $filtros = []): array
    {
        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.usuario = :usuarioId')
            ->setParameter('usuarioId', $usuarioId)
            ->orderBy('t.fechaCreacion', 'DESC');

        if (!empty($filtros['estado'])) {
            $qb->andWhere('t.estado = :estado')
                ->setParameter('estado', $filtros['estado']);
        }

        if (!empty($filtros['texto'])) {
            $qb->andWhere('t.titulo LIKE :texto OR t.descripcion LIKE :texto')
                ->setParameter('texto', '%' . $filtros['texto'] . '%');
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Recupera las tareas pendientes de un usuario cuya fecha límite
     * está comprendida entre la fecha actual y la fecha calculada
     * a partir del intervalo indicado.
     * @return Tarea[]
     */
    public function findPendientesPorVencer(int $usuarioId, \DateInterval $intervalo)
    {
        $ahora  = new \DateTimeImmutable();
        $limite = $ahora->add($intervalo);

        return $this->createQueryBuilder('t')
            ->andWhere('t.usuario = :usuario') // filtramos por el usuario indicado
            ->andWhere('t.estado = :estado')
            ->andWhere('t.fechaLimite IS NOT NULL')
            ->andWhere('t.fechaLimite >= :ahora')    // límite inferior
            ->andWhere('t.fechaLimite <= :limite')
            ->setParameter('usuario', $usuarioId) // enlazamos el valor del parámetro
            ->setParameter('estado', 'pendiente')
            ->setParameter('ahora', $ahora, \Doctrine\DBAL\Types\Types::DATETIME_IMMUTABLE)
            ->setParameter('limite', $limite, \Doctrine\DBAL\Types\Types::DATETIME_IMMUTABLE)
            ->orderBy('t.fechaLimite', 'ASC') // ordenamos por fecha limite
            ->getQuery()
            ->getResult();
    }



    //    /**
    //     * @return Tarea[] Returns an array of Tarea objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Tarea
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()a
    //        ;
    //    }
}
