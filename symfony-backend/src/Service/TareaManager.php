<?php

namespace App\Service;

use App\Entity\Tarea;
use App\Entity\Usuario;
use App\Repository\TareaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TareaManager
{
    /**
     * Estados permitidos; evita repetir strings mágicos por toda la aplicación.
     */
    private const ESTADOS_VALIDOS = ['pendiente', 'en_progreso', 'completada'];

    public function __construct(
        private readonly TareaRepository $tareaRepository,      // reutilizamos las consultas personalizadas
        private readonly EntityManagerInterface $entityManager  // coordina los INSERT/UPDATE/DELETE
    ) {}

    /**
     * Valida que la fecha de límite nunca sea anterior a la fecha actual.
     */
    public function validarFechaLimite(?\DateTimeInterface $fechaLimite, \DateTimeInterface $fechaCreacion): void
    {
        if ($fechaLimite !== null && $fechaLimite < $fechaCreacion) {
            throw new BadRequestHttpException('La fecha límite no puede ser anterior a la fecha de creación.');
        }
    }


    /**
     * Devuelve las tareas de un usuario aplicando filtros opcionales.
     */
    public function listarPorUsuario(int $usuarioId, array $filtros = []): array
    {
        return $this->tareaRepository->buscarPorFiltros($usuarioId, $filtros);
    }

    /**
     * Crea una nueva tarea y la persiste en base de datos.
     */
    public function crear(array $payload, Usuario $usuario): Tarea
    {
        $titulo = $payload['titulo'] ?? null;           // título obligatorio
        $estado = $payload['estado'] ?? 'pendiente';    // valor por defecto cuando no llega del cliente

        if (!$titulo) {
            throw new BadRequestHttpException('El título es obligatorio.');
        }

        if (!in_array($estado, self::ESTADOS_VALIDOS, true)) {
            throw new BadRequestHttpException('Estado no válido.');
        }

        $fechaCreacion = new \DateTimeImmutable();
        $fechaLimite = isset($payload['fechaLimite'])
            ? new \DateTime($payload['fechaLimite'])
            : null;

        $this->validarFechaLimite($fechaLimite, $fechaCreacion); //llamada al metodo para verificar la fecha limite

        $tarea = (new Tarea())
            ->setTitulo($titulo)
            ->setDescripcion($payload['descripcion'] ?? null)
            ->setEstado($estado)
            ->setFechaCreacion($fechaCreacion) //Lo creo fuera ya que la uso para verificar
            ->setFechaLimite($fechaLimite) //Lo creo fuera para verificar
            ->setUsuario($usuario); // asociamos la tarea al dueño

        $this->entityManager->persist($tarea); // prepara el INSERT
        $this->entityManager->flush();         // ejecuta la consulta en la base de datos

        return $tarea;
    }

    /**
     * Actualiza los datos principales de una tarea existente.
     */
    public function actualizar(Tarea $tarea, array $payload): Tarea
    {
        if (isset($payload['titulo']) && $payload['titulo'] === '') {
            throw new BadRequestHttpException('El título no puede quedar vacío.');
        }

        if (isset($payload['estado']) && !in_array($payload['estado'], self::ESTADOS_VALIDOS, true)) {
            throw new BadRequestHttpException('Estado no válido.');
        }

        $tarea
            ->setTitulo($payload['titulo'] ?? $tarea->getTitulo())
            ->setDescripcion($payload['descripcion'] ?? $tarea->getDescripcion())
            ->setEstado($payload['estado'] ?? $tarea->getEstado())
            ->setFechaLimite(isset($payload['fechaLimite']) ? new \DateTime($payload['fechaLimite']) : $tarea->getFechaLimite());

        $this->entityManager->flush(); // sincroniza los cambios mediante UPDATE

        return $tarea;
    }

    /**
     * Cambia únicamente el estado de la tarea.
     */
    public function cambiarEstado(Tarea $tarea, string $estado): Tarea
    {
        if (!in_array($estado, self::ESTADOS_VALIDOS, true)) {
            throw new BadRequestHttpException('Estado no válido.');
        }

        $tarea->setEstado($estado);
        $this->entityManager->flush();

        return $tarea;
    }

    /**
     * Elimina la tarea de la base de datos.
     */
    public function eliminar(Tarea $tarea): void
    {
        $this->entityManager->remove($tarea);
        $this->entityManager->flush();
    }

    /**
     * Garantiza que la tarea existe y pertenece al usuario que hace la petición.
     */
    public function aseguraPerteneceAUsuario(?Tarea $tarea, int $usuarioId): Tarea
    {
        if (!$tarea || $tarea->getUsuario()?->getId() !== $usuarioId) {
            throw new NotFoundHttpException('Tarea no encontrada para este usuario.');
        }

        return $tarea;
    }
}
