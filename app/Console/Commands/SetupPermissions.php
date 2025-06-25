<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SetupPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'setup role and permissions ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('Setting up permissions and roles...');

        // إنشاء الصلاحيات
        $this->createPermissions();

        // إنشاء الأدوار
        $this->createRoles();

        // ربط الصلاحيات بالأدوار
        $this->assignPermissionsToRoles();

        $this->info('Permissions and roles setup completed!');


    }

    private function createPermissions()
    {

        $permissions =[

            //user
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view roles',
            'view permissions',

            //doctor
            'view doctors',
            'view only section doctors', // Secretary
            'create doctors',
            'edit doctors',
            'delete doctors',
            'view samples', // LAB Doctor



            //patient
            'view patients',  //  Admin / Reception
            'view only my patients', // Doctor
            'view only my section patients', // Secretary
            'create patients',
            'edit patients',
            'delete patients',

            //management
            'creat schedule_workHours',
            'edit schedule_workHours',
            'delete schedule_workHours',
            'edit workHours doctors', // Admin / Secretary
            'edit workHours nurses',  //Admin
            'manage-settings',

            //appointment
            'create appointments',
            'edit appointments',
            'delete appointments',
            'view own appointments',  // Doctor
            'view section appointments', //Secretary
            'view my appointments', //  Patient

            // السجلات الطبية
            'view-medical-records section ' , // Secretary
            'view-medical-records own ', // Doctor
            'view_medical-records my only', //Patient
            'edit medical history', // patient / Secretary

            //report
            'view reports',
            'view reports own',
            'view reports section',
            'view reports my only ',

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        $this->info('Permissions created');
    }

    private function createRoles()
    {
        $roles =[
            'Admin',
            'Doctor_clinic',
            'Doctor_lab',
            'Patient',
            'Secretary',
            'Reception',
            'Driver',
            'Nurse',

        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
        $this->info(' Roles created');
    }

    private function assignPermissionsToRoles()
    {

        $adminRole = Role::findByName('Admin');
        $adminPermission = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view roles',
            'view permissions',
            'view doctors',
            'create doctors',
            'edit doctors',
            'delete doctors',
            'view patients' ,
            'create patients',
            'edit patients',
            'delete patients',
            'creat schedule_workHours',
            'edit schedule_workHours',
            'delete schedule_workHours',
            'edit workHours doctors',
            'edit workHours nurses',
            'manage-settings',

        ];
        $adminRole->givePermissionTo($adminPermission);

        $doctorClinicRole = Role::findByName('Doctor_clinic');
        $doctorClinicPermission = [
            'edit doctors',
            'view own appointments',
            'view-medical-records own ',
            'view reports own',
        ];
        $doctorClinicRole->givePermissionTo($doctorClinicPermission);


        $doctorLabRole = Role::findByName('Doctor_lab');
        $doctorLabPermission = [
            'edit doctors',
            'view samples',
            'view reports own',
        ];
        $doctorLabRole->givePermissionTo($doctorLabPermission);


        $patientRole = Role::findByName('Patient');
        $patientPermission = [
            'edit patients',
            'view my appointments',
            'view_medical-records my only',
            'view reports my only ',
            'edit medical history',
        ];
        $patientRole->givePermissionTo($patientPermission);


        $secretaryRole = Role::findByName('Secretary');
        $secretaryPermission = [
            'edit users',
            'view only section doctors',
            'view only my section patients',
            'edit workHours doctors',
            'create appointments',
            'view section appointments',
            'view-medical-records section ' ,
            'edit medical history',
            'view reports section',
        ];
        $secretaryRole->givePermissionTo($secretaryPermission);

        $nurseRole = Role::findByName('Nurse');
        $nursePermission = [
            'edit users',
            'view only my patients',
        ];
        $nurseRole->givePermissionTo($nursePermission);


        $receptionRole = Role::findByName('Reception');
        $receptionPermission = [
            'edit users',
            'view doctors',
            'view patients',
            'create appointments',
            'edit appointments',
            'delete appointments',
            'view reports',
        ];
        $receptionRole->givePermissionTo($receptionPermission);

        $this->info(' Permissions assigned to roles');

    }

}
