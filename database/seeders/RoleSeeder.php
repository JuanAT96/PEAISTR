<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles
        $role_administrativo = Role::create(['name' => 'administrativo']);
        $role_docente = Role::create(['name' => 'docente']);
        $role_coordinador = Role::create(['name' => 'coordinador']);
        $role_vicerrectorado = Role::create(['name' => 'vicerrectorado']);
        $role_estudiante = Role::create(['name' => 'estudiante']);

        // Permisos para la gestión de roles
        $permission_create_roles = Permission::create(['name' => 'crear roles']);
        $permission_read_roles = Permission::create(['name' => 'leer roles']);
        $permission_update_roles = Permission::create(['name' => 'actualizar roles']);
        $permission_delete_roles = Permission::create(['name' => 'eliminar roles']);

        // Permisos para la gestión de usuarios
        $permission_create_users = Permission::create(['name' => 'crear usuarios']);
        $permission_read_users = Permission::create(['name' => 'leer usuarios']);
        $permission_update_users = Permission::create(['name' => 'actualizar usuarios']);
        $permission_delete_users = Permission::create(['name' => 'eliminar usuarios']);

        // Permisos para la gestión de carreras
        $permission_create_careers = Permission::create(['name' => 'crear carreras']);
        $permission_read_careers = Permission::create(['name' => 'leer carreras']);
        $permission_update_careers = Permission::create(['name' => 'actualizar carreras']);
        $permission_delete_careers = Permission::create(['name' => 'eliminar carreras']);

        // Permisos para la gestión de asignaturas
        $permission_create_subjects = Permission::create(['name' => 'crear asignaturas']);
        $permission_read_subjects = Permission::create(['name' => 'leer asignaturas']);
        $permission_update_subjects = Permission::create(['name' => 'actualizar asignaturas']);
        $permission_delete_subjects = Permission::create(['name' => 'eliminar asignaturas']);

        // Permisos para la gestión de periodos académicos
        $permission_create_academic_periods = Permission::create(['name' => 'crear periodos académicos']);
        $permission_read_academic_periods = Permission::create(['name' => 'leer periodos académicos']);
        $permission_update_academic_periods = Permission::create(['name' => 'actualizar periodos académicos']);
        $permission_delete_academic_periods = Permission::create(['name' => 'eliminar periodos académicos']);

        // Permisos para la gestión de requisitos de asignaturas
        $permission_create_subject_requisites = Permission::create(['name' => 'crear requisitos de asignaturas']);
        $permission_read_subject_requisites = Permission::create(['name' => 'leer requisitos de asignaturas']);
        $permission_update_subject_requisites = Permission::create(['name' => 'actualizar requisitos de asignaturas']);
        $permission_delete_subject_requisites = Permission::create(['name' => 'eliminar requisitos de asignaturas']);

        // Permisos para la gestión de competencias específicas
        $permission_create_specific_competences = Permission::create(['name' => 'crear competencias específicas']);
        $permission_read_specific_competences = Permission::create(['name' => 'leer competencias específicas']);
        $permission_update_specific_competences = Permission::create(['name' => 'actualizar competencias específicas']);
        $permission_delete_specific_competences = Permission::create(['name' => 'eliminar competencias específicas']);

        // Permisos para la gestión de competencias genéricas
        $permission_create_generic_competences = Permission::create(['name' => 'crear competencias genéricas']);
        $permission_read_generic_competences = Permission::create(['name' => 'leer competencias genéricas']);
        $permission_update_generic_competences = Permission::create(['name' => 'actualizar competencias genéricas']);
        $permission_delete_generic_competences = Permission::create(['name' => 'eliminar competencias genéricas']);

        // Permisos para la gestión de estrategias de enseñanza-aprendizaje
        $permission_create_teaching_strategies = Permission::create(['name' => 'crear estrategias de enseñanza-aprendizaje']);
        $permission_read_teaching_strategies = Permission::create(['name' => 'leer estrategias de enseñanza-aprendizaje']);
        $permission_update_teaching_strategies = Permission::create(['name' => 'actualizar estrategias de enseñanza-aprendizaje']);
        $permission_delete_teaching_strategies = Permission::create(['name' => 'eliminar estrategias de enseñanza-aprendizaje']);

        // Permisos para la gestión de PEA
        $permission_create_pea = Permission::create(['name' => 'crear pea']);
        $permission_read_pea = Permission::create(['name' => 'leer pea']);
        $permission_update_pea = Permission::create(['name' => 'actualizar pea']);
        $permission_delete_pea = Permission::create(['name' => 'eliminar pea']);

        // Permisos para la gestión de asignaciones (carrera con asignatura y asignatura con docente)
        $permission_create_assignments = Permission::create(['name' => 'crear asignaciones']);
        $permission_read_assignments = Permission::create(['name' => 'leer asignaciones']);
        $permission_update_assignments = Permission::create(['name' => 'actualizar asignaciones']);
        $permission_delete_assignments = Permission::create(['name' => 'eliminar asignaciones']);

        // Permisos para la gestión de unidades curriculares
        $permission_create_curricular_units = Permission::create(['name' => 'crear unidades curriculares']);
        $permission_read_curricular_units = Permission::create(['name' => 'leer unidades curriculares']);
        $permission_update_curricular_units = Permission::create(['name' => 'actualizar unidades curriculares']);
        $permission_delete_curricular_units = Permission::create(['name' => 'eliminar unidades curriculares']);

        // Permisos para la gestión de contenidos de enseñanza
        $permission_create_teaching_contents = Permission::create(['name' => 'crear contenidos de enseñanza']);
        $permission_read_teaching_contents = Permission::create(['name' => 'leer contenidos de enseñanza']);
        $permission_update_teaching_contents = Permission::create(['name' => 'actualizar contenidos de enseñanza']);
        $permission_delete_teaching_contents = Permission::create(['name' => 'eliminar contenidos de enseñanza']);

        // Permisos para la gestión de evaluaciones
        $permission_create_evaluations = Permission::create(['name' => 'crear evaluaciones']);
        $permission_read_evaluations = Permission::create(['name' => 'leer evaluaciones']);
        $permission_update_evaluations = Permission::create(['name' => 'actualizar evaluaciones']);
        $permission_delete_evaluations = Permission::create(['name' => 'eliminar evaluaciones']);

        // Permisos para la gestión de bibliografías
        $permission_create_bibliographies = Permission::create(['name' => 'crear bibliografías']);
        $permission_read_bibliographies = Permission::create(['name' => 'leer bibliografías']);
        $permission_update_bibliographies = Permission::create(['name' => 'actualizar bibliografías']);
        $permission_delete_bibliographies = Permission::create(['name' => 'eliminar bibliografías']);

        // Permisos para la gestión de aprobaciones
        $permission_create_approvals = Permission::create(['name' => 'crear aprobaciones']);
        $permission_read_approvals = Permission::create(['name' => 'leer aprobaciones']);
        $permission_update_approvals = Permission::create(['name' => 'actualizar aprobaciones']);
        $permission_delete_approvals = Permission::create(['name' => 'eliminar aprobaciones']);

        // Permisos para la gestión de la configuración del sistema
        $permission_configurar_sistema = Permission::create(['name' => 'configurar sistema']);

        // Permisos para la gestión de copias de seguridad
        //  $permission_generar_copias_seguridad = Permission::create(['name' => 'generar copias de seguridad']);
        //  $permission_restaurar_copias_seguridad = Permission::create(['name' => 'restaurar copias de seguridad']);

        // Permisos para reportes
        $permission_generar_reportes_peas = Permission::create(['name' => 'generar reportes peas']);
        $permission_ver_reportes_generales = Permission::create(['name' => 'ver reportes generales']);
        $permission_ver_reportes_carrera = Permission::create(['name' => 'ver reportes carrera']);

        // Permisos específicos para el rol de estudiante (solo lectura)
        $permission_consultar_pea = Permission::create(['name' => 'consultar pea']);

        // Permisos para la gestión de usuarios que suben documentos
        // $permission_subir_documentos = Permission::create(['name' => 'subir documentos']);

        // Agrupación de permisos por rol
        $permission_administrativo = [
            $permission_create_roles,
            $permission_read_roles,
            $permission_update_roles,
            $permission_delete_roles,
            $permission_create_users,
            $permission_read_users,
            $permission_update_users,
            $permission_delete_users,
            $permission_create_careers,
            $permission_read_careers,
            $permission_update_careers,
            $permission_delete_careers,
            $permission_create_subjects,
            $permission_read_subjects,
            $permission_update_subjects,
            $permission_delete_subjects,
            $permission_create_academic_periods,
            $permission_read_academic_periods,
            $permission_update_academic_periods,
            $permission_delete_academic_periods,
            $permission_create_subject_requisites,
            $permission_read_subject_requisites,
            $permission_update_subject_requisites,
            $permission_delete_subject_requisites,
            $permission_create_specific_competences,
            $permission_read_specific_competences,
            $permission_update_specific_competences,
            $permission_delete_specific_competences,
            $permission_create_generic_competences,
            $permission_read_generic_competences,
            $permission_update_generic_competences,
            $permission_delete_generic_competences,
            $permission_create_teaching_strategies,
            $permission_read_teaching_strategies,
            $permission_update_teaching_strategies,
            $permission_delete_teaching_strategies,
            $permission_create_pea,
            $permission_read_pea,
            $permission_update_pea,
            $permission_delete_pea,
            $permission_create_assignments,
            $permission_read_assignments,
            $permission_update_assignments,
            $permission_delete_assignments,
            $permission_create_curricular_units,
            $permission_read_curricular_units,
            $permission_update_curricular_units,
            $permission_delete_curricular_units,
            $permission_create_teaching_contents,
            $permission_read_teaching_contents,
            $permission_update_teaching_contents,
            $permission_delete_teaching_contents,
            $permission_create_evaluations,
            $permission_read_evaluations,
            $permission_update_evaluations,
            $permission_delete_evaluations,
            $permission_create_bibliographies,
            $permission_read_bibliographies,
            $permission_update_bibliographies,
            $permission_delete_bibliographies,
            $permission_create_approvals,
            $permission_read_approvals,
            $permission_update_approvals,
            $permission_delete_approvals,
            $permission_configurar_sistema,
            //$permission_generar_copias_seguridad,
            //$permission_restaurar_copias_seguridad,
            $permission_generar_reportes_peas,
            $permission_ver_reportes_generales,
            $permission_ver_reportes_carrera,
            //$permission_subir_documentos,
        ];

        $permission_docente = [
            $permission_create_subject_requisites,
            $permission_read_subject_requisites,
            $permission_update_subject_requisites,
            $permission_delete_subject_requisites,
            $permission_create_specific_competences,
            $permission_read_specific_competences,
            $permission_update_specific_competences,
            $permission_delete_specific_competences,
            $permission_create_generic_competences,
            $permission_read_generic_competences,
            $permission_update_generic_competences,
            $permission_delete_generic_competences,
            $permission_create_teaching_strategies,
            $permission_read_teaching_strategies,
            $permission_update_teaching_strategies,
            $permission_delete_teaching_strategies,
            $permission_create_pea,
            $permission_read_pea,
            $permission_update_pea,
            $permission_delete_pea,
            $permission_create_curricular_units,
            $permission_read_curricular_units,
            $permission_update_curricular_units,
            $permission_delete_curricular_units,
            $permission_create_teaching_contents,
            $permission_read_teaching_contents,
            $permission_update_teaching_contents,
            $permission_delete_teaching_contents,
            $permission_create_evaluations,
            $permission_read_evaluations,
            $permission_update_evaluations,
            $permission_delete_evaluations,
            $permission_create_bibliographies,
            $permission_read_bibliographies,
            $permission_update_bibliographies,
            $permission_delete_bibliographies,
            //  $permission_subir_documentos,
        ];

        $permission_coordinador = [$permission_read_careers, $permission_read_subjects, $permission_read_academic_periods, $permission_read_subject_requisites, $permission_read_specific_competences, $permission_read_generic_competences, $permission_read_teaching_strategies, $permission_read_pea, $permission_read_curricular_units, $permission_read_teaching_contents, $permission_read_evaluations, $permission_read_bibliographies, $permission_read_approvals, $permission_ver_reportes_carrera];

        $permission_vicerrectorado = [$permission_ver_reportes_generales];

        $permission_estudiante = [$permission_consultar_pea, $permission_read_subjects, $permission_read_curricular_units, $permission_read_teaching_contents];

        // Sincronización de permisos a roles
        $role_administrativo->syncPermissions($permission_administrativo);
        $role_docente->syncPermissions($permission_docente);
        $role_coordinador->syncPermissions($permission_coordinador);
        $role_vicerrectorado->syncPermissions($permission_vicerrectorado);
        $role_estudiante->syncPermissions($permission_estudiante);
    }
}
