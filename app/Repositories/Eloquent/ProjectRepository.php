<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\RepositoryException;
use App\Models\Project;
use App\Models\User;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function store(array $data, User $user): Project
    {
        try {
            return $user->projects()->create($data);
        } catch (\Throwable $e) {
            throw new RepositoryException(
                "Erro ao criar projeto: " . $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e
            );
        }
    }

    public function update(array $data, Project $project): Project
    {
        try {
            $project->update($data);

            return $project;
        } catch (\Throwable $e) {
            throw new RepositoryException(
                "Erro ao atualiza projeto: " . $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e
            );
        }
    }

    public function destroy(Project $project): Project
    {
        try {
            $project->delete();
            return $project;
        } catch (\Throwable $e) {
            throw new RepositoryException(
                "Erro ao deletar projeto: " . $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e
            );
        }
    }
}