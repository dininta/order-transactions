<?php
namespace App\Console\Commands;

use App\Model\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AdminCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'user:new-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new admin.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $name = $this->input->getArgument('name');
        $email = $this->input->getArgument('email');
        $password = $this->input->getArgument('password');

        $admin = new User();
        $admin->name = $name;
        $admin->email = $email;
        $admin->password = $password;
        $admin->group_type = User::ADMIN;

        if ($admin->save()) {
            $this->info('New Admin has been successfully created!!');
        } else {
            $this->error('Failed to create new admin.');
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Admin\' name'],
            ['email', InputArgument::REQUIRED, 'Admin\' email'],
            ['password', InputArgument::REQUIRED, 'Admin\'s password'],
        ];
    }
}
