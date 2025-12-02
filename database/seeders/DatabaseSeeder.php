<?php

namespace Database\Seeders;


// LAS TABLAS A RECTIFICAR Y/O MODIFICAR SON: AsignaturaSeccion, DetalleMatricula, Profesor, DetallesProfesor, Horarios, Pago, Matricula, Soporte
// ==========================================
// 1. IMPORTS (Van fuera de la clase)
// ==========================================
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\Facultad;
use App\Models\Escuela;
use App\Models\Carrera;
use App\Models\User;
use App\Models\Profesor;
use App\Models\Estudiante;
use App\Models\DetallesEstudiante;
use App\Models\PeriodoAcademico;
use App\Models\Asignatura;
use App\Models\MallaCurricular;
use App\Models\Edificio;
use App\Models\Aula;
use App\Models\BloquesHorarios;
use App\Models\AsignaturaSeccion;
use App\Models\Horarios;
use App\Models\Matricula;
use App\Models\DetalleMatricula;
use App\Models\Prerequisito;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==========================================================
        // 2. LÓGICA DEL SCRIPT (Va dentro del método run)
        // ==========================================================
        
        \Illuminate\Database\Eloquent\Model::unguard();

        echo ">>> 1. LIMPIANDO BASE DE DATOS... \n";
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Array de tablas a limpiar
        $tablas = [
            'detalle_matriculas','matriculas','prerequisitos','soportes','pagos',
            'horarios','asignatura_seccions','bloques_horarios','malla_curricular',
            'asignaturas','periodo_academicos','detalles_estudiantes','estudiantes',
            'detalles_profesores','profesores','usuarios','aulas','edificios',
            'especialidades','carreras','escuelas','facultades'
        ];

        foreach ($tablas as $tabla) { 
            if (Schema::hasTable($tabla)) {
                DB::table($tabla)->truncate(); 
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo ">>> 2. CARGANDO JERARQUÍA INSTITUCIONAL... \n";

        // A. FACULTADES
        $facultades = [
            ['01', 'Facultad de Administración'], ['02', 'Facultad de Ciencias Económicas'], ['03', 'Facultad de Ciencias Financieras y Contables'],
            ['04', 'Facultad de Ciencias Sociales'], ['05', 'Facultad de Derecho y Ciencia Política'], ['06', 'Facultad de Educación'],
            ['07', 'Facultad de Humanidades'], ['08', 'Facultad de Arquitectura y Urbanismo'], ['09', 'Facultad de Ingeniería Civil'],
            ['10', 'Facultad de Ingeniería Industrial y de Sistemas'], ['11', 'Facultad de Ingeniería Geográfica, Ambiental y Ecoturismo'],
            ['12', 'Facultad de Oceanografía, Pesquería, Ciencias Alimentarias y Acuicultura'],
            ['13', 'Facultad de Ingeniería Electrónica e Informática'], // FIEI
            ['14', 'Facultad de Ciencias Naturales y Matemática'], ['15', 'Facultad de Medicina Hipólito Unanue'],
            ['16', 'Facultad de Odontología'], ['17', 'Facultad de Tecnología Médica'], ['18', 'Facultad de Psicología']
        ];

        foreach ($facultades as $fac) {
            Facultad::create(['codigo_interno' => $fac[0], 'nombre' => $fac[1], 'direccion' => 'Campus Central']);
        }
        $fiei = Facultad::where('codigo_interno', '13')->first();

        // B. ESCUELAS (FIEI)
        $escuelas = [
            ['01', 'Escuela Profesional de Ingeniería Electrónica'],
            ['02', 'Escuela Profesional de Ingeniería Informática'],
            ['03', 'Escuela Profesional de Ingeniería Mecatrónica'],
            ['04', 'Escuela Profesional de Ingeniería de Telecomunicaciones']
        ];
        foreach ($escuelas as $esc) {
            Escuela::create(['facultad_id' => $fiei->id, 'nombre' => $esc[1], 'codigo_interno' => $esc[0]]);
        }
        // Nota: Asegúrate que el código sea correcto, en tu script original usabas '02' para Info
        $escuelaInfo = Escuela::where('codigo_interno', '02')->where('facultad_id', $fiei->id)->first();

        // C. CARRERAS
        $carreras = [
            ['01', 'Ingeniería Electrónica'],
            ['02', 'Ingeniería Informática'],
            ['03', 'Ingeniería Mecatrónica'],
            ['04', 'Ingeniería de Telecomunicaciones']
        ];
        foreach ($carreras as $car) {
            $esc = Escuela::where('codigo_interno', $car[0])->where('facultad_id', $fiei->id)->first();
            if($esc) {
                Carrera::create(['escuela_id' => $esc->id, 'nombre' => $car[1], 'codigo_interno' => $car[0]]);
            }
        }
        $carreraInfo = Carrera::where('codigo_interno', '02')->first();

        echo ">>> 3. CARGANDO MALLA CURRICULAR Y CURSOS... \n";

	// [LegacyID, Semestre, Codigo, Nombre, Condicion, Creditos, PrerrequisitosString]
        $cursosData = [
            // PRIMER SEMESTRE [cite: 2]
            [1, 1, '100549', 'Lenguaje y Comunicación', 'Obligatorio', 3, NULL],
            [2, 1, '100550', 'Filosofía y Ética', 'Obligatorio', 3, NULL],
            [3, 1, '100551', 'Metodología del Trabajo Universitario', 'Obligatorio', 2, NULL],
            [4, 1, '100552', 'Actividades Culturales y Deportivas', 'Obligatorio', 1, NULL],
            [5, 1, '101007', 'Matemática Básica', 'Obligatorio', 4, NULL],
            [6, 1, '100553', 'Fundamentos de Cálculo', 'Obligatorio', 3, NULL],
            [7, 1, '101008', 'Introducción a la Ingeniería Informática', 'Obligatorio', 3, NULL],
            [8, 1, '100375', 'Inglés I', 'Obligatorio', 1, NULL],

            // SEGUNDO SEMESTRE 
            [9, 2, '100555', 'Liderazgo y Desarrollo Personal', 'Obligatorio', 3, NULL],
            [10, 2, '100556', 'Medio Ambiente y Desarrollo Sostenible', 'Obligatorio', 3, NULL],
            [11, 2, '100557', 'Tecnologías de La Información y Sociedad', 'Obligatorio', 2, NULL],
            [12, 2, '100442', 'Sociología', 'Obligatorio', 2, NULL],
            [13, 2, '101009', 'Matemática Discreta para Informática', 'Obligatorio', 3, '5,7'],
            [14, 2, '100558', 'Cálculo Integral', 'Obligatorio', 4, '6'],
            [15, 2, '100560', 'Física', 'Obligatorio', 4, NULL],
            [16, 2, '100382', 'Inglés II', 'Obligatorio', 1, NULL],

            // TERCER SEMESTRE 
            [17, 3, '100003', 'Psicología Organizacional', 'Obligatorio', 2, NULL],
            [18, 3, '103263', 'Estadística', 'Obligatorio', 3, NULL],
            [19, 3, '100377', 'Metodología de la Investigación Científica', 'Obligatorio', 3, NULL],
            [20, 3, '100561', 'Geopolítica y Realidad Nacional', 'Obligatorio', 3, NULL],
            [21, 3, '101010', 'Lógica Digital', 'Obligatorio', 3, '13,15'],
            [22, 3, '101011', 'Matemática Aplicada', 'Obligatorio', 3, '14'],
            [23, 3, '101012', 'Lenguaje de Programación I', 'Obligatorio', 4, '13'],
            [24, 3, '100387', 'Inglés III', 'Obligatorio', 1, NULL],

            // CUARTO SEMESTRE [cite: 5]
            [25, 4, '100581', 'Métodos Numéricos', 'Obligatorio', 3, '18'],
            [26, 4, '101013', 'Base de Datos I', 'Obligatorio', 3, '23'],
            [27, 4, '101014', 'Teoría de Comunicaciones', 'Obligatorio', 3, '21'],
            [28, 4, '101015', 'Electrónica Digital', 'Obligatorio', 4, '21'],
            [29, 4, '101016', 'Arquitectura y Organización del Computador', 'Obligatorio', 3, '22'],
            [30, 4, '101017', 'Lenguaje de Programación II', 'Obligatorio', 3, '23'],
            [31, 4, '101018', 'Inglés Aplicado a la Informática I', 'Obligatorio', 3, '24'],

            // QUINTO SEMESTRE [cite: 7]
            [32, 5, '101019', 'Contabilidad y Costos', 'Obligatorio', 3, '25'],
            [33, 5, '101020', 'Base de Datos II', 'Obligatorio', 3, '26'],
            [34, 5, '101021', 'Dispositivos Móviles I', 'Obligatorio', 4, '27, 28'],
            [35, 5, '100636', 'Proyecto Integrador I', 'Obligatorio', 3, '26, 30'],
            [36, 5, '101022', 'Lenguaje de Programación III', 'Obligatorio', 3, '30'],
            [37, 5, '101023', 'Inglés Aplicado a la Informática II', 'Obligatorio', 3, '31'],
            [38, 5, 'EL001', 'Electivo 1 (Certificación Progresiva CP5)', 'Electivo', 3, '28,30'],

            // SEXTO SEMESTRE 
            [39, 6, '101024', 'Investigación de Operaciones', 'Obligatorio', 3, '32'],
            [40, 6, '101025', 'Gestión Y Análisis de Datos e Información', 'Obligatorio', 4, '33'],
            [41, 6, '101026', 'Dispositivos Móviles II', 'Obligatorio', 3, '34'],
            [42, 6, '100993', 'Redes y Conectividad', 'Obligatorio', 3, '34'],
            [43, 6, '101027', 'Teleinformática I', 'Obligatorio', 3, '29'],
            [44, 6, '101028', 'Inglés Aplicado a la Informática III', 'Obligatorio', 3, '37'],
            [45, 6, 'EL002', 'Electivo 2 (Certificación Progresiva CP6)', 'Electivo', 3, '35,33'],

            // SÉPTIMO SEMESTRE [cite: 9]
            [46, 7, '101029', 'Ingeniería de Sistemas de Información', 'Obligatorio', 4, '39'],
            [47, 7, '100908', 'Formulación y Evaluación de Proyectos', 'Obligatorio', 3, '40'],
            [48, 7, '101030', 'Proyecto Integrador II', 'Obligatorio', 4, '41'],
            [49, 7, '101031', 'Planeamiento Estratégico de la Información', 'Obligatorio', 3, '40'],
            [50, 7, '101032', 'Ingeniería Económica', 'Obligatorio', 2, '39'],
            [51, 7, '101033', 'Teleinformática II', 'Obligatorio', 3, '43'],
            [52, 7, 'EL003', 'Electivo 3 (Certificación Progresiva CP7)', 'Electivo', 3, '41,42'],

            // OCTAVO SEMESTRE 
            [53, 8, '101034', 'Finanzas para Empresas', 'Obligatorio', 3, '39'],
            [54, 8, '101035', 'Dinámica de Sistemas de Información', 'Obligatorio', 3, '46'],
            [55, 8, '101036', 'Tópicos Avanzados en Programación', 'Obligatorio', 3, '48'],
            [56, 8, '101037', 'Innovación y Tecnología', 'Obligatorio', 3, '49'],
            [57, 8, '101038', 'Sistemas Operativos', 'Obligatorio', 3, '41'],
            [58, 8, '101039', 'Proyectos Informáticos', 'Obligatorio', 3, '49'],

            // NOVENO SEMESTRE [cite: 13]
            [59, 9, '101040', 'Prospectiva Empresarial', 'Obligatorio', 3, '53,56'],
            [60, 9, '101041', 'Simulación de Sistemas Informáticos y Empresariales', 'Obligatorio', 3, '54'],
            [61, 9, '101042', 'Control y Calidad de Software', 'Obligatorio', 3, '55'],
            [62, 9, '101043', 'Proyecto Integrador en Dispositivos Móviles', 'Obligatorio', 3, '55'],
            [63, 9, '101044', 'Sistemas de Información Gerencial', 'Obligatorio', 3, '53'],
            [64, 9, '100597', 'Taller de Tesis I', 'Obligatorio', 2, '58'],
            [65, 9, '100996', 'Practicas Pre Profesionales I', 'Obligatorio', 2, '58'],

            // DÉCIMO SEMESTRE [cite: 14]
            [66, 10, '101045', 'Gerencia y Consultoria Informática', 'Obligatorio', 3, '59'],
            [67, 10, '101046', 'Ingeniería del Conocimiento', 'Obligatorio', 3, '60'],
            [68, 10, '101047', 'Seguridad y Auditoria Informática', 'Obligatorio', 3, '61'],
            [69, 10, '101048', 'Tecnologías Emergentes', 'Obligatorio', 3, '62'],
            [70, 10, '101049', 'Tecnología E-Business', 'Obligatorio', 3, '62'],
            [71, 10, '100603', 'Taller de Tesis II', 'Obligatorio', 2, '64'],
            [72, 10, '101003', 'Practicas Pre Profesionales II', 'Obligatorio', 2, '65']
        ];

        $idMap = [];

        // 1. Insertar Cursos y Malla
        foreach ($cursosData as $c) {
            // CORRECCIÓN: Se ajustó al fillable de Asignatura (codigo_asignatura, nombre, creditos)
            // Se eliminó 'asig_prerequi' porque no existe en el modelo.
            $asignatura = Asignatura::create([
                'codigo_asignatura' => $c[2],
                'nombre'            => $c[3],
                'creditos'          => $c[5],
            ]);
            
            // Guardamos el ID real de la base de datos mapeado al ID temporal (Legacy) del array
            $idMap[$c[0]] = $asignatura->id;

            // CORRECCIÓN: Se ajustó al fillable de MallaCurricular (asignatura_id, carrera_id, semestre, tipo_curso, activo)
            // Se cambió 'asig_oblig' por 'tipo_curso' y 'estado' por 'activo'.
            MallaCurricular::create([
                'carrera_id'    => $carreraInfo->id, // Asumiendo que $carreraInfo existe en el contexto superior
                'asignatura_id' => $asignatura->id,
                'semestre'      => $c[1],
                'tipo_curso'    => $c[4], // Ej: 'Obligatorio' o 'Electivo'
                'activo'        => 1      // Se establece en 1 (true) para indicar activo
            ]);
        }

        // 2. Procesar Prerrequisitos
        foreach ($cursosData as $c) {
            // Verificar si hay string de prerrequisitos (índice 6)
            if (!empty($c[6])) {
                $prereqs = explode(',', $c[6]);
                $cursoRealId = $idMap[$c[0]]; 

                foreach ($prereqs as $pId) {
                    $reqLegacyId = trim($pId);
                    
                    // Verificar que el ID del requisito exista en nuestro mapa de IDs creados
                    if (isset($idMap[$reqLegacyId])) {
                        Prerequisito::create([
                            'asignatura_id' => $cursoRealId,       // El curso actual
                            'requisito_id'  => $idMap[$reqLegacyId] // El curso que es requisito
                        ]);
                    }
                }
            }
        }

        echo ">>> 4. CREANDO PERIODO... \n";

        // Periodo
        $periodo = PeriodoAcademico::create(['codigo' => '2025-1', 'fecha_inicio' => '2025-04-01', 'fecha_fin' => '2025-08-01', 'turno' => 'M']);
        $edificio = Edificio::create(['nombre' => 'Pabellón B', 'facultad_id' => $fiei->id]);
        $aula = Aula::create(['nombre_aula' => 'B-301', 'edificio_id' => $edificio->id, 'tipo' => 'Laboratorio', 'capacidad' => 40, 'piso' => 3, 'estado' => 'Activo']);

        // Bloques horarios
        $bloques = [];
        $bloques[] = BloquesHorarios::create(['hora_inicio' => '08:00:00', 'hora_fin' => '10:00:00']); 
        $bloques[] = BloquesHorarios::create(['hora_inicio' => '10:00:00', 'hora_fin' => '12:00:00']); 
        $bloques[] = BloquesHorarios::create(['hora_inicio' => '12:00:00', 'hora_fin' => '14:00:00']); 
        $bloques[] = BloquesHorarios::create(['hora_inicio' => '14:00:00', 'hora_fin' => '16:00:00']); 
        $bloques[] = BloquesHorarios::create(['hora_inicio' => '16:00:00', 'hora_fin' => '18:00:00']);

	echo ">>> 4.1. CREANDO LOS USUARIOS DEL SISTEMA \n";

	
        // Admin
        User::create(['username' => 'admin', 'password' => Hash::make('admin'), 'rol' => 'Admin', 'estado' => 'Activo']);


        // Estudiante 1
        $codigoUniversitario = '2025010963';
        $userEst = User::create(['username' => $codigoUniversitario, 'password' => Hash::make('2025010963'), 'rol' => 'Estudiante', 'estado' => 'Activo']);
        $estudiante = Estudiante::create([
            'usuario_id' => $userEst->id, 'carrera_id' => $carreraInfo->id, 'anio_ingreso' => 2025, 
            'codigo_universitario' => $codigoUniversitario, 'correo_institucional' => $codigoUniversitario.'@unfv.edu.pe',
            'nombres' => 'Geral', 'apellidos' => 'Lujan', 'dni' => 71717171, 'estado' => 'Activo'
        ]);
        DetallesEstudiante::create(['estudiante_id' => $estudiante->id, 'estado_matricula' => 'Regular', 'fecha_ingreso' => now(), 'promedio_general' => 0]);


	// Estudiante 2
        $codigoUniversitario = '2022015428';
        $userEst = User::create(['username' => $codigoUniversitario, 'password' => Hash::make('2022015428'), 'rol' => 'Estudiante', 'estado' => 'Activo']);
        $estudiante = Estudiante::create([
            'usuario_id' => $userEst->id, 'carrera_id' => $carreraInfo->id, 'anio_ingreso' => 2022, 
            'codigo_universitario' => $codigoUniversitario, 'correo_institucional' => $codigoUniversitario.'@unfv.edu.pe',
            'nombres' => 'Josue', 'apellidos' => 'Albarracin Rivera', 'dni' => 72389816, 'estado' => 'Activo'
        ]);
        DetallesEstudiante::create(['estudiante_id' => $estudiante->id, 'estado_matricula' => 'Regular', 'fecha_ingreso' => now(), 'promedio_general' => 0]);
       
	// Estudiante 3 2021017455@unfv.edu.pe
        $codigoUniversitario = '2021017455';
        $userEst = User::create(['username' => $codigoUniversitario, 'password' => Hash::make('2021017455'), 'rol' => 'Estudiante', 'estado' => 'Activo']);
        $estudiante = Estudiante::create([
            'usuario_id' => $userEst->id, 'carrera_id' => $carreraInfo->id, 'anio_ingreso' => 2021, 
            'codigo_universitario' => $codigoUniversitario, 'correo_institucional' => $codigoUniversitario.'@unfv.edu.pe',
            'nombres' => 'Hugo Andre', 'apellidos' => 'Alfaro Garcia', 'dni' => 70707070, 'estado' => 'Activo'
        ]);
        DetallesEstudiante::create(['estudiante_id' => $estudiante->id, 'estado_matricula' => 'Regular', 'fecha_ingreso' => now(), 'promedio_general' => 0]);

        \Illuminate\Database\Eloquent\Model::reguard();

        echo "\n============================================\n";
        echo ">>> ¡CARGA FINAL COMPLETADA! <<<\n";
        echo "============================================\n";
        echo "Total Cursos Insertados: " . count($cursosData) . "\n";
        echo "Alumnos registrados con su codigo universtario y la contraseña el mismo codigo\n";
    }
}