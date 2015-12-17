<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{

    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $unitTesting = true;

        $testEnvironment = 'testing';

        return require __DIR__.'/../../bootstrap/start.php';
    }

    public function createCompany()
    {
        $c = Company::create(["name" => "testC".rand(0, 10000)]);
        return $c;
    }

    public function resetEvents()
    {
        $models = $this->modelsToReset();
        DB::statement('SET foreign_key_checks = 0;');
        if (count($models))
            foreach ($models as $model) {
                call_user_func(array($model, 'truncate'));
                call_user_func(array($model, 'flushEventListeners'));
                call_user_func(array($model, 'boot'));
            }
        DB::statement('SET foreign_key_checks = 1;');
    }

    protected function modelsToReset()
    {
        return [];
    }
}
