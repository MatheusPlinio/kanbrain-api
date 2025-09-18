<?php

namespace App\Repositories\Contracts;
use \App\Models\Project;
use App\Models\User;

interface ProjectRepositoryInterface
{
    /**
     * Create a new Project
     *
     * @param array{
     *     name: string,
     *     description: string|null
     * } $data
     * @return Project 
     * 
     * @throws \App\Exceptions\RepositoryException
     */
    public function store(array $data, User $user): Project;

    /**
     * Update a Project
     *
     * @param array{
     *     name: string,
     *     description: string|null
     * } $data
     * @return Project 
     * 
     * @throws \App\Exceptions\RepositoryException
     */
    public function update(array $data, Project $project): Project;

    /**
     * Delete a Project
     *
     * @param Project $project
     * @return Project The deleted project instance
     *
     * @throws \App\Exceptions\RepositoryException
     */
    public function destroy(Project $project): Project;
}