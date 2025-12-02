<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

// Importar Modelos
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
use App\Models\Prerrequisito;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Database\Eloquent\Model::unguard();

        echo ">>> 1. LIMPIANDO BASE DE DATOS... \n";
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $tablas = [
            'detalle_matriculas','pagos','matriculas','prerrequisitos','soportes',
            'horarios','asignatura_seccions','bloques_horarios','malla_curricular',
            'asignaturas','periodo_academicos','detalles_estudiantes','estudiantes',
            'detalles_profesores','profesores','usuarios','aulas','edificios',
            'especialidades','carreras','escuelas','facultades',
            'sessions', 'cache', 'jobs'
        ];

        foreach ($tablas as $tabla) { 
            if (Schema::hasTable($tabla)) {
                DB::table($tabla)->truncate(); 
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo ">>> 2. CARGANDO JERARQUÍA INSTITUCIONAL... \n";

        // A. FACULTADES
        $fiei = Facultad::create([
            'codigo_interno' => '13', 
            'nombre' => 'Facultad de Ingeniería Electrónica e Informática', 
            'direccion' => 'Campus Central'
        ]);

        // B. ESCUELAS (FIEI)
        $escuelaInfo = Escuela::create([
            'facultad_id' => $fiei->id, 
            'nombre' => 'Escuela Profesional de Ingeniería Informática', 
            'codigo_interno' => '02'
        ]);

        // C. CARRERAS
        $carreraInfo = Carrera::create([
            'escuela_id' => $escuelaInfo->id, 
            'nombre' => 'Ingeniería Informática', 
            'codigo_interno' => '02'
        ]);

        echo ">>> 3. CARGANDO MALLA CURRICULAR... \n";

        // [LegacyID, Semestre, Codigo, Nombre, Condicion, Creditos, PrerrequisitosString]
        $cursosData = [
            // CICLO 1
            [1, 1, '100549', 'Lenguaje y Comunicación', 'Obligatorio', 3, NULL],
            [2, 1, '100550', 'Filosofía y Ética', 'Obligatorio', 3, NULL],
            [3, 1, '100551', 'Metodología del Trabajo Universitario', 'Obligatorio', 2, NULL],
            [4, 1, '100552', 'Actividades Culturales y Deportivas', 'Obligatorio', 1, NULL],
            [5, 1, '101007', 'Matemática Básica', 'Obligatorio', 4, NULL],
            [6, 1, '100553', 'Fundamentos de Cálculo', 'Obligatorio', 3, NULL],
            [7, 1, '101008', 'Introducción a la Ingeniería Informática', 'Obligatorio', 3, NULL],
            [8, 1, '100375', 'Inglés I', 'Obligatorio', 1, NULL],
            // CICLO 2
            [9, 2, '100555', 'Liderazgo y Desarrollo Personal', 'Obligatorio', 3, NULL],
            [10, 2, '100556', 'Medio Ambiente y Desarrollo Sostenible', 'Obligatorio', 3, NULL],
            [11, 2, '100557', 'Tecnologías de La Información y Sociedad', 'Obligatorio', 2, NULL],
            [12, 2, '100442', 'Sociología', 'Obligatorio', 2, NULL],
            [13, 2, '101009', 'Matemática Discreta para Informática', 'Obligatorio', 3, '5,7'],
            [14, 2, '100558', 'Cálculo Integral', 'Obligatorio', 4, '6'],
            [15, 2, '100560', 'Física', 'Obligatorio', 4, NULL],
            [16, 2, '100382', 'Inglés II', 'Obligatorio', 1, NULL],
            // CICLO 3
            [17, 3, '100003', 'Psicología Organizacional', 'Obligatorio', 2, NULL],
            [18, 3, '103263', 'Estadística', 'Obligatorio', 3, NULL],
            [19, 3, '100377', 'Metodología de la Investigación Científica', 'Obligatorio', 3, NULL],
            [20, 3, '100561', 'Geopolítica y Realidad Nacional', 'Obligatorio', 3, NULL],
            [21, 3, '101010', 'Lógica Digital', 'Obligatorio', 3, '13,15'],
            [22, 3, '101011', 'Matemática Aplicada', 'Obligatorio', 3, '14'],
            [23, 3, '101012', 'Lenguaje de Programación I', 'Obligatorio', 4, '13'],
            [24, 3, '100387', 'Inglés III', 'Obligatorio', 1, NULL],
            // CICLO 4
            [25, 4, '100581', 'Métodos Numéricos', 'Obligatorio', 3, '18'],
            [26, 4, '101013', 'Base de Datos I', 'Obligatorio', 3, '23'],
            [27, 4, '101014', 'Teoría de Comunicaciones', 'Obligatorio', 3, '21'],
            [28, 4, '101015', 'Electrónica Digital', 'Obligatorio', 4, '21'],
            [29, 4, '101016', 'Arquitectura y Organización del Computador', 'Obligatorio', 3, '22'],
            [30, 4, '101017', 'Lenguaje de Programación II', 'Obligatorio', 3, '23'],
            [31, 4, '101018', 'Inglés Aplicado a la Informática I', 'Obligatorio', 3, '24'],
            // CICLO 5
            [32, 5, '101019', 'Contabilidad y Costos', 'Obligatorio', 3, '25'],
            [33, 5, '101020', 'Base de Datos II', 'Obligatorio', 3, '26'],
            [34, 5, '101021', 'Dispositivos Móviles I', 'Obligatorio', 4, '27, 28'],
            [35, 5, '100636', 'Proyecto Integrador I', 'Obligatorio', 3, '26, 30'],
            [36, 5, '101022', 'Lenguaje de Programación III', 'Obligatorio', 3, '30'],
            [37, 5, '101023', 'Inglés Aplicado a la Informática II', 'Obligatorio', 3, '31'],
            [38, 5, 'EL001', 'Electivo 1 (Certificación Progresiva CP5)', 'Electivo', 3, '28,30'],
            [90, 5, '101050', 'Microprocesadores', 'Obligatorio', 3, '29'],
            // CICLO 6
            [39, 6, '101024', 'Investigación de Operaciones', 'Obligatorio', 3, '32'],
            [40, 6, '101025', 'Gestión Y Análisis de Datos e Información', 'Obligatorio', 4, '33'],
            [41, 6, '101026', 'Dispositivos Móviles II', 'Obligatorio', 3, '34'],
            [42, 6, '100993', 'Redes y Conectividad', 'Obligatorio', 3, '34'],
            [43, 6, '101027', 'Teleinformática I', 'Obligatorio', 3, '29'],
            [44, 6, '101028', 'Inglés Aplicado a la Informática III', 'Obligatorio', 3, '37'],
            [45, 6, 'EL002', 'Electivo 2 (Certificación Progresiva CP6)', 'Electivo', 3, '35,33'],
            [91, 6, '101052', 'Inteligencia Artificial (Elec)', 'Electivo', 3, '30'],
            // CICLO 7
            [46, 7, '101029', 'Ingeniería de Sistemas de Información', 'Obligatorio', 4, '39'],
            [47, 7, '100908', 'Formulación y Evaluación de Proyectos', 'Obligatorio', 3, '40'],
            [48, 7, '101030', 'Proyecto Integrador II', 'Obligatorio', 4, '41'],
            [49, 7, '101031', 'Planeamiento Estratégico de la Información', 'Obligatorio', 3, '40'],
            [50, 7, '101032', 'Ingeniería Económica', 'Obligatorio', 2, '39'],
            [51, 7, '101033', 'Teleinformática II', 'Obligatorio', 3, '43'],
            [52, 7, 'EL003', 'Electivo 3 (Certificación Progresiva CP7)', 'Electivo', 3, '41,42'],
            [92, 7, '101054', 'Realidad Virtual (Electivo)', 'Electivo', 3, '41'],
            // CICLO 8
            [53, 8, '101034', 'Finanzas para Empresas', 'Obligatorio', 3, '39'],
            [54, 8, '101035', 'Dinámica de Sistemas de Información', 'Obligatorio', 3, '46'],
            [55, 8, '101036', 'Tópicos Avanzados en Programación', 'Obligatorio', 3, '48'],
            [56, 8, '101037', 'Innovación y Tecnología', 'Obligatorio', 3, '49'],
            [57, 8, '101038', 'Sistemas Operativos', 'Obligatorio', 3, '41'],
            [58, 8, '101039', 'Proyectos Informáticos', 'Obligatorio', 3, '49'],
            [93, 3, '100886', 'Estadística', 'Obligatorio', 3, NULL],
        ];

        $idMap = [];

        foreach ($cursosData as $c) {
            $asignatura = Asignatura::firstOrCreate(
                ['codigo_asignatura' => $c[2]],
                ['nombre' => $c[3], 'creditos' => $c[5]]
            );
            
            $idMap[$c[0]] = $asignatura->id;

            MallaCurricular::firstOrCreate([
                'carrera_id'    => $carreraInfo->id,
                'asignatura_id' => $asignatura->id,
                'semestre'      => $c[1],
                'tipo_curso'    => $c[4],
                'activo'        => 1
            ]);
        }

        foreach ($cursosData as $c) {
            if (!empty($c[6])) {
                $prereqs = explode(',', $c[6]);
                $cursoRealId = $idMap[$c[0]]; 
                foreach ($prereqs as $pId) {
                    $reqLegacyId = trim($pId);
                    if (isset($idMap[$reqLegacyId])) {
                        Prerrequisito::firstOrCreate([
                            'asignatura_id' => $cursoRealId,
                            'requisito_id'  => $idMap[$reqLegacyId]
                        ]);
                    }
                }
            }
        }

        echo ">>> 4. CREANDO PERIODOS Y HORARIOS... \n";

        // CREAR AMBOS PERIODOS (IMPAR Y PAR)
        $periodoImpar = PeriodoAcademico::create(['codigo' => '2025-1', 'fecha_inicio' => '2025-04-01', 'fecha_fin' => '2025-07-31', 'turno' => 'M']);
        $periodoPar   = PeriodoAcademico::create(['codigo' => '2025-2', 'fecha_inicio' => '2025-08-01', 'fecha_fin' => '2025-12-20', 'turno' => 'M']);

        // DATOS DE HORARIOS
        // Correcciones aplicadas:
        // - AULA 12 para Métodos Numéricos (Choque en AULA 11 Lunes)
        // - AULA 12 para Cálculo Integral (Choque en AULA 11 Jueves)
        // - INF-3 para Disp. Móviles II (Choque en INF-1 Jueves)
        // - INF-3 para Gestión y Análisis (Choque en INF-2 Jueves)
        
        $horariosRaw = [
            ['1','100375','Inglés I','1','0','2','2','M','B','PEREZ SAMANAMUD MIGUEL','120','ODONTOLOGIA','LUNES','10:30','12:10'],
            ['1','100375','Inglés I','1','0','2','2','M','A','PEREZ SAMANAMUD MIGUEL','111','ODONTOLOGIA','MARTES','08:50','10:30'],
            ['1','100549','Lenguaje y Comunicación','3','2','2','4','M','B','GRADOS ESPINOZA ANNA','120','ODONTOLOGIA','LUNES','14:40','18:00'],
            ['1','100549','Lenguaje y Comunicación','3','2','2','4','M','A','GRADOS ESPINOZA ANNA','111','ODONTOLOGIA','MIERCOLES','14:40','18:00'],
            ['1','100550','Filosofía y Ética','3','2','2','4','M','B','AGUILAR TITO','120','ODONTOLOGIA','MIERCOLES','07:10','10:30'],
            ['1','100550','Filosofía y Ética','3','2','2','4','M','A','AGUILAR TITO','111','ODONTOLOGIA','MIERCOLES','10:30','13:50'],
            ['1','100551','Metodología del Trabajo Univ.','2','1','2','3','M','B','NEGREIROS','120','ODONTOLOGIA','VIERNES','07:10','09:40'],
            ['1','100551','Metodología del Trabajo Univ.','2','1','2','3','M','A','NEGREIROS','111','ODONTOLOGIA','VIERNES','09:40','12:10'],
            ['1','100552','Actividades Culturales','1','0','2','2','M','A','SANCHEZ CASTILLO EDDYE','111','ODONTOLOGIA','SABADO','10:30','12:10'],
            ['1','100552','Actividades Culturales','1','0','2','2','M','B','SANCHEZ CASTILLO EDDYE','120','ODONTOLOGIA','SABADO','12:10','13:50'],
            ['1','100553','Fundamentos de Cálculo','3','2','2','4','M','B','(SIN DOCENTE)','120','ODONTOLOGIA','JUEVES','07:10','10:30'],
            ['1','100553','Fundamentos de Cálculo','3','2','2','4','M','A','(SIN DOCENTE)','111','ODONTOLOGIA','JUEVES','10:30','13:50'],
            ['1','101007','Matemática Básica','4','2','-','-','M','B','(SIN DOCENTE)','120','ODONTOLOGIA','LUNES','12:10','13:50'],
            ['1','101007','Matemática Básica','4','3','-','-','M','A','TARAZONA GIRALDO MIGUEL','111','ODONTOLOGIA','JUEVES','07:10','09:40'],
            ['1','101007','Matemática Básica','4','2','-','-','M','A','TARAZONA GIRALDO MIGUEL','111','ODONTOLOGIA','VIERNES','07:10','08:50'],
            ['1','101007','Matemática Básica','4','3','5','-','M','B','(SIN DOCENTE)','120','ODONTOLOGIA','VIERNES','09:40','12:10'],
            ['1','101008','Intro. a la Ing. Informática','3','2','-','-','M','A','RODRIGUEZ RODRIGUEZ CIRO','LAB2','IQUIQUE','LUNES','07:10','08:50'],
            ['1','101008','Intro. a la Ing. Informática','3','2','4','-','M','A','RODRIGUEZ RODRIGUEZ CIRO','403','ODONTOLOGIA','LUNES','08:50','10:30'],
            ['2','100382','Inglés II','1','0','2','2','M','A','PEREZ SAMANAMUD MIGUEL','AULA 20','ODONTOLOGIA','JUEVES','09:40','11:20'],
            ['2','100382','Inglés II','1','0','2','2','M','B','PEREZ SAMANAMUD MIGUEL','AULA 11','ODONTOLOGIA','JUEVES','11:20','13:00'],
            ['2','100442','Sociología','2','1','2','3','M','B','NEGREIROS CRIADO MANUEL','AULA 20','ODONTOLOGIA','LUNES','07:10','09:40'],
            ['2','100442','Sociología','2','1','2','3','M','A','PORRAS LAVALLE RAUL','AULA 11','ODONTOLOGIA','JUEVES','07:10','09:40'],
            ['2','100555','Liderazgo y Desarrollo P.','3','2','2','4','M','A','GALINDO VALDIVIA EFRAIN','AULA 11','ODONTOLOGIA','LUNES','07:10','10:30'],
            ['2','100555','Liderazgo y Desarrollo P.','3','2','2','4','M','B','GALINDO VALDIVIA EFRAIN','AULA 20','ODONTOLOGIA','MIERCOLES','07:10','10:30'],
            ['2','100556','Medio Amb. y Des. Sost.','3','2','2','4','M','A','VASQUEZ REYES LUIS','AULA 11','ODONTOLOGIA','LUNES','10:30','13:50'],
            ['2','100556','Medio Amb. y Des. Sost.','3','2','2','4','M','B','VASQUEZ REYES LUIS','AULA 20','ODONTOLOGIA','VIERNES','07:10','10:30'],
            ['2','100557','Tec. de Inf. y Com (TIC)','2','0','4','4','M','A','RODRIGUEZ RODRIGUEZ CIRO','LAB INF-1','IQUIQUE','LUNES','07:10','10:30'],
            ['2','100557','Tec. de Inf. y Com (TIC)','2','0','4','4','M','B','RODRIGUEZ RODRIGUEZ CIRO','LAB INF-1','IQUIQUE','MARTES','07:10','10:30'],
            ['2','100558','Cálculo Integral','4','3','2','5','M','A','TARAZONA GIRALDO MIGUEL','AULA 12','ODONTOLOGIA','JUEVES','07:10','09:40'], // AULA 12 (Fix)
            ['2','100558','Cálculo Integral','4','3','2','5','M','A','TARAZONA GIRALDO MIGUEL','AULA 11','ODONTOLOGIA','MARTES','07:10','08:50'],
            ['2','100558','Cálculo Integral','4','3','2','5','M','B','NUÑEZ MEJIA JOEL','404','IQUIQUE','MARTES','10:30','14:40'],
            ['2','100560','Física','4','2','4','6','M','B','ASTUÑAUPA BALVIN VICTOR','AULA 20','ODONTOLOGIA','JUEVES','07:10','12:10'],
            ['2','100560','Física','4','2','4','6','M','A','ASTUÑAUPA BALVIN VICTOR','AULA 11','ODONTOLOGIA','JUEVES','11:20','13:50'],
            ['2','100560','Física','4','2','4','6','M','A','ASTUÑAUPA BALVIN VICTOR','AULA 11','ODONTOLOGIA','VIERNES','12:10','14:40'],
            ['2','101009','Matemática Discreta','3','2','2','4','M','B','FLORES EULOGIO RAMIRO','404','IQUIQUE','SABADO','07:10','10:30'],
            ['2','101009','Matemática Discreta','3','2','2','4','M','A','FLORES EULOGIO RAMIRO','404','IQUIQUE','SABADO','10:30','13:50'],
            ['3','100003','Psicología Organizacional','3','1','2','4','M','B','(SIN DOCENTE)','403','IQUIQUE','MIERCOLES','07:10','09:40'],
            ['3','100003','Psicología Organizacional','2','1','2','3','M','A','(SIN DOCENTE)','404','IQUIQUE','VIERNES','08:50','11:20'],
            ['3','100377','Metodología de la Inv. C.','3','2','2','4','M','B','RODRIGUEZ RODRIGUEZ CIRO','403','IQUIQUE','LUNES','10:30','13:50'],
            ['3','100377','Metodología de la Inv. C.','3','2','2','4','M','A','RODRIGUEZ RODRIGUEZ CIRO','404','IQUIQUE','MARTES','10:30','13:50'],
            ['3','100387','Inglés III','1','0','2','2','M','B','PEREZ SAMANAMUD MIGUEL','403','IQUIQUE','JUEVES','07:10','08:50'],
            ['3','100387','Inglés III','1','0','2','2','M','A','PEREZ SAMANAMUD MIGUEL','404','IQUIQUE','VIERNES','07:10','08:50'],
            ['3','100561','Geopolítica y R. Nacional','0','2','2','4','M','A','AGUILAR TITO','404','IQUIQUE','JUEVES','07:10','10:30'],
            ['3','100561','Geopolítica y R. Nacional','3','2','2','4','T','B','(SIN DOCENTE)','403','IQUIQUE','JUEVES','12:10','15:30'],
            ['3','100886','Estadística','3','2','2','4','M','B','FLORES EULOGIO RAMIRO','403','IQUIQUE','SABADO','07:10','10:30'],
            ['3','100886','Estadística','3','2','2','4','M','A','FLORES EULOGIO RAMIRO','404','IQUIQUE','SABADO','10:30','13:50'],
            ['3','101010','Lógica Digital','3','2','2','4','T','A','CUBA AGUILAR CESAR','404','IQUIQUE','MARTES','14:40','18:00'],
            ['3','101010','Lógica Digital','3','2','2','4','T','B','CUBA AGUILAR CESAR','403','IQUIQUE','MIERCOLES','13:50','17:10'],
            ['3','101011','Matemática Aplicada','3','2','2','4','M','A','(SIN DOCENTE)','404','IQUIQUE','JUEVES','10:30','13:50'],
            ['3','101011','Matemática Aplicada','2','2','2','3','M','B','(SIN DOCENTE)','404','IQUIQUE','VIERNES','11:20','14:40'],
            ['3','101012','Lenguaje de Programación I','4','4','6','-','M','A','(SIN DOCENTE)','LAB2','IQUIQUE','LUNES','08:50','12:10'],
            ['3','101012','Lenguaje de Programación I','4','2','6','-','M','B','CRISPIN SANCHEZ IVAN','403','IQUIQUE','MARTES','08:50','10:30'],
            ['3','101012','Lenguaje de Programación I','4','2','6','-','M','A','(SIN DOCENTE)','404','IQUIQUE','MIERCOLES','07:10','08:50'],
            ['3','101012','Lenguaje de Programación I','4','4','6','-','M','B','CRISPIN SANCHEZ IVAN','LAB2','IQUIQUE','MIERCOLES','10:30','13:50'],
            ['4','100581','Métodos Numéricos','3','2','2','4','M','A','AGUILAR DIAZ TITO','AULA 12','ODONTOLOGIA','LUNES','10:30','13:50'], // AULA 12 (Fix)
            ['4','100581','Métodos Numéricos','3','2','2','4','M','B','AGUILAR DIAZ TITO','504','ODONTOLOGIA','VIERNES','07:10','10:30'],
            ['4','101013','Base de Datos I','3','2','2','4','M','A','CRISPIN SANCHEZ IVAN','INF-2','IQUIQUE','JUEVES','09:40','13:00'],
            ['4','101013','Base de Datos I','3','2','2','4','M','B','CRISPIN SANCHEZ IVAN','INF-1','IQUIQUE','JUEVES','10:50','13:50'],
            ['4','101014','Teoría de Comunicaciones','3','2','2','4','T','A','MARIN VASQUEZ JHONY','INF-2','IQUIQUE','MIERCOLES','18:00','21:20'],
            ['4','101014','Teoría de Comunicaciones','3','2','2','4','T','B','MARIN VASQUEZ JHONY','INF-1','IQUIQUE','VIERNES','18:50','22:10'],
            ['4','101015','Electrónica Digital','4','2','4','6','M','A','CASTRO VARGAS CRISTIAN','INF-1','IQUIQUE','MARTES','10:30','12:10'],
            ['4','101015','Electrónica Digital','4','2','4','6','M','B','CASTRO VARGAS CRISTIAN','INF-2','IQUIQUE','MIERCOLES','10:30','13:50'],
            ['4','101015','Electrónica Digital','4','2','4','6','M','B','CASTRO VARGAS CRISTIAN','INF-1','IQUIQUE','JUEVES','12:10','13:50'],
            ['4','101015','Electrónica Digital','4','2','4','6','M','A','CASTRO VARGAS CRISTIAN','INF-2','IQUIQUE','JUEVES','14:40','18:00'],
            ['4','101016','Arq. y Organización Comp.','3','2','2','4','M','A','CASTRO VARGAS CRISTIAN','INF-1','IQUIQUE','MIERCOLES','07:10','10:30'],
            ['4','101016','Arq. y Organización Comp.','3','2','2','4','M','B','CASTRO VARGAS CRISTIAN','INF-2','IQUIQUE','JUEVES','07:10','10:30'],
            ['4','101017','Lenguaje de Programación II','3','2','2','4','M','B','CRISPIN SANCHEZ IVAN','INF-2','IQUIQUE','LUNES','07:10','10:30'],
            ['4','101017','Lenguaje de Programación II','3','2','2','6','M','A','PASTOR CASTILLO JOSE','INF-1','IQUIQUE','LUNES','17:10','22:10'],
            ['4','101018','Inglés Aplicado a la Inf. I','3','2','2','4','M','A','PEREZ SAMANAMUD MIGUEL','AULA 11','ODONTOLOGIA','LUNES','07:10','08:50'],
            ['4','101018','Inglés Aplicado a la Inf. I','3','2','2','4','M','A','PEREZ SAMANAMUD MIGUEL','AULA 20','ODONTOLOGIA','MARTES','07:10','08:50'],
            ['4','101018','Inglés Aplicado a la Inf. I','3','2','2','4','M','B','PEREZ SAMANAMUD MIGUEL','AULA 20','ODONTOLOGIA','MARTES','08:50','12:10'],
            ['5','100636','Proyecto Integrador I','3','2','2','4','M','A','FLORES MASIAS EDWARD','LAB2','IQUIQUE','MARTES','08:00','11:20'],
            ['5','100636','Proyecto Integrador I','3','2','2','4','M','B','FLORES MASIAS EDWARD','LAB1','IQUIQUE','JUEVES','08:50','12:10'],
            ['5','101019','Contabilidad y Costos','3','2','-','-','M','A','(SIN DOCENTE)','LAB1','IQUIQUE','LUNES','08:00','09:40'],
            ['5','101019','Contabilidad y Costos','3','2','-','-','M','A','(SIN DOCENTE)','404','IQUIQUE','LUNES','09:40','11:20'],
            ['5','101019','Contabilidad y Costos','3','2','2','4','M','B','(SIN DOCENTE)','LAB1','IQUIQUE','SABADO','08:50','12:10'],
            ['5','101020','Base de Datos II','3','2','-','-','T','A','CRISPIN SANCHEZ IVAN','LAB2','IQUIQUE','MIERCOLES','13:50','15:30'],
            ['5','101020','Base de Datos II','3','2','4','-','T','A','CRISPIN SANCHEZ IVAN','404','IQUIQUE','MIERCOLES','15:30','17:10'],
            ['5','101020','Base de Datos II','3','2','-','-','T','B','CRISPIN SANCHEZ IVAN','LAB2','IQUIQUE','JUEVES','13:50','15:30'],
            ['5','101020','Base de Datos II','3','2','4','-','T','B','CRISPIN SANCHEZ IVAN','403','IQUIQUE','JUEVES','15:30','17:10'],
            ['5','101021','Dispositivos Móviles I','4','2','5','-','T','A','CASAS MIRANDA ROBERTO','LAB2','IQUIQUE','MARTES','13:50','15:30'],
            ['5','101021','Dispositivos Móviles I','4','3','5','-','T','B','CASAS MIRANDA ROBERTO','403','IQUIQUE','MARTES','15:30','18:00'],
            ['5','101021','Dispositivos Móviles I','4','2','5','-','T','B','CASAS MIRANDA ROBERTO','LAB1','IQUIQUE','SABADO','12:10','13:50'],
            ['5','101021','Dispositivos Móviles I','4','3','5','-','T','A','CASAS MIRANDA ROBERTO','404','IQUIQUE','SABADO','15:30','18:00'],
            ['5','101022','Lenguaje de Programación III','3','2','4','-','T','A','(SIN DOCENTE)','LAB2','IQUIQUE','VIERNES','13:00','14:40'],
            ['5','101022','Lenguaje de Programación III','3','2','4','-','T','B','(SIN DOCENTE)','LAB1','IQUIQUE','VIERNES','13:50','15:30'],
            ['5','101022','Lenguaje de Programación III','3','2','-','-','T','A','(SIN DOCENTE)','404','IQUIQUE','VIERNES','14:40','16:20'],
            ['5','101022','Lenguaje de Programación III','3','2','4','-','T','B','(SIN DOCENTE)','403','IQUIQUE','VIERNES','15:30','17:10'],
            ['5','101023','Inglés Aplicado a la Inf. II','3','2','2','4','M','A','PEREZ SAMANAMUD MIGUEL','403','IQUIQUE','JUEVES','08:50','12:10'],
            ['5','101023','Inglés Aplicado a la Inf. II','3','2','2','4','M','B','PEREZ SAMANAMUD MIGUEL','403','IQUIQUE','VIERNES','09:40','13:00'],
            ['5','101050','Microprocesadores','3','2','-','-','T','B','(SIN DOCENTE)','LAB2','IQUIQUE','MARTES','11:20','13:00'],
            ['5','101050','Microprocesadores','3','2','-','-','T','B','(SIN DOCENTE)','403','IQUIQUE','MARTES','13:00','14:40'],
            ['5','101050','Microprocesadores','3','2','-','-','T','A','CASTRO VARGAS CRISTIAN','404','IQUIQUE','JUEVES','13:50','15:30'],
            ['5','101050','Microprocesadores','3','2','-','-','T','A','CASTRO VARGAS CRISTIAN','LAB1','IQUIQUE','JUEVES','15:30','17:10'],
            /* ['6','100993','Redes y Conectividad','3','2','2','4','M','A','SANCHEZ TORRES JUAN','403','IQUIQUE','MIERCOLES','13:00','17:10'],
            ['6','100993','Redes y Conectividad','3','2','2','4','M','B','COLLANTES INGA ZOILA','INF-1','IQUIQUE','LUNES','19:40','23:00'],
            ['6','101024','Investigación de Operaciones','3','2','2','4','M','A','NARCISO LIVIA GUILLERMO','404','IQUIQUE','MARTES','14:40','18:00'],
            ['6','101024','Investigación de Operaciones','3','2','2','4','M','B','NARCISO LIVIA GUILLERMO','403','IQUIQUE','SABADO','13:50','17:10'],
            ['6','101025','Gestión y Análisis de Datos','4','2','4','6','T','A','CRISPIN SANCHEZ IVAN','INF-2','IQUIQUE','JUEVES','14:40','18:00'],
            ['6','101025','Gestión y Análisis de Datos','4','2','4','6','T','A','CRISPIN SANCHEZ IVAN','LAB-1','IQUIQUE','VIERNES','17:10','18:50'],
            ['6','101025','Gestión y Análisis de Datos','4','2','4','6','T','B','ESPINOZA SILVERIO EDGAR','INF-2','IQUIQUE','VIERNES','17:10','22:10'],
            ['6','101025','Gestión y Análisis de Datos','4','2','4','6','T','B','ESPINOZA SILVERIO EDGAR','INF-3','IQUIQUE','JUEVES','14:40','18:00'], // INF-3 (Fix)
            ['6','101026','Dispositivos Móviles II','3','2','2','4','T','A','CASAS MIRANDA ROBERTO','INF-1','IQUIQUE','JUEVES','13:50','17:10'],
            ['6','101026','Dispositivos Móviles II','3','2','2','4','T','B','ESPINOZA SILVERIO EDGAR','INF-3','IQUIQUE','JUEVES','13:50','17:10'], // INF-3 (Fix)
            ['6','101027','Teleinformática I','3','2','2','4','M','A','CASAS MIRANDA ROBERTO','INF-2','IQUIQUE','VIERNES','13:50','17:10'],
            ['6','101027','Teleinformática I','3','2','2','4','M','B','SANCHEZ CASTILLO EDDYE','INF-2','IQUIQUE','VIERNES','17:10','20:30'],
            ['6','101028','Inglés Aplicado a la Inf. III','3','2','2','4','T','A','PEREZ SAMANAMUD MIGUEL','403','IQUIQUE','VIERNES','09:40','13:00'],
            ['6','101028','Inglés Aplicado a la Inf. III','3','2','2','4','T','B','GIL LOPEZ JOSE','404','IQUIQUE','VIERNES','13:50','17:10'],
            ['6','101052','Inteligencia Artificial (Elec)','3','2','2','6','T','A','RODRIGUEZ RODRIGUEZ CIRO','INF-1','IQUIQUE','LUNES','10:30','13:00'],
            ['6','101052','Inteligencia Artificial (Elec)','3','2','2','6','T','A','RODRIGUEZ RODRIGUEZ CIRO','INF-1','IQUIQUE','MARTES','10:30','13:00'],
            ['6','101052','Inteligencia Artificial (Elec)','3','2','2','4','T','B','MATOS MANGUINURI JEAN','403','IQUIQUE','VIERNES','18:50','22:20'],
            ['7','100908','Formulación y Ev. Proyectos','3','2','2','4','N','A','PAREDES VARGAS RONALD','404','IQUIQUE','MIERCOLES','18:50','22:10'],
            ['7','100908','Formulación y Ev. Proyectos','3','2','2','4','N','B','ESPINOZA SILVERIO EDGAR','403','IQUIQUE','VIERNES','19:40','23:00'],
            ['7','101029','Ing. de Sist. de Información','4','3','5','-','N','A','(SIN DOCENTE)','404','IQUIQUE','LUNES','18:00','20:30'],
            ['7','101029','Ing. de Sist. de Información','4','2','-','-','N','A','(SIN DOCENTE)','404','IQUIQUE','MARTES','18:00','19:40'],
            ['7','101029','Ing. de Sist. de Información','4','2','-','-','N','B','(SIN DOCENTE)','403','IQUIQUE','MARTES','21:20','23:00'],
            ['7','101029','Ing. de Sist. de Información','4','3','5','-','N','B','(SIN DOCENTE)','403','IQUIQUE','JUEVES','17:10','19:40'],
            ['7','101030','Proyecto Integrador II','4','4','6','-','N','B','(SIN DOCENTE)','403','IQUIQUE','LUNES','15:30','18:50'],
            ['7','101030','Proyecto Integrador II','4','2','-','-','T','A','PASTOR CASTILLO JOSE','404','IQUIQUE','MIERCOLES','17:10','18:50'],
            ['7','101030','Proyecto Integrador II','4','2','-','-','N','B','(SIN DOCENTE)','403','IQUIQUE','MIERCOLES','20:30','22:10'],
            ['7','101030','Proyecto Integrador II','4','4','6','-','T','A','PASTOR CASTILLO JOSE','404','IQUIQUE','VIERNES','16:20','19:40'],
            ['7','101031','Plan. Estratégico de la Inf.','3','2','2','4','N','B','SICOS PEÑALOZA CARLOS','403','IQUIQUE','LUNES','18:50','22:10'],
            ['7','101031','Plan. Estratégico de la Inf.','3','2','2','4','N','A','PAREDES VARGAS RONALD','404','IQUIQUE','MARTES','19:40','23:00'],
            ['7','101032','Ingeniería Económica','2','1','2','3','N','A','DIAZ FLORES PAUL','404','IQUIQUE','LUNES','20:30','23:00'],
            ['7','101032','Ingeniería Económica','2','1','2','3','N','B','(SIN DOCENTE)','403','IQUIQUE','JUEVES','19:40','22:10'],
            ['7','101033','Teleinformática II','3','2','-','-','N','B','(SIN DOCENTE)','LAB2','IQUIQUE','MIERCOLES','17:10','18:50'],
            ['7','101033','Teleinformática II','3','2','4','-','N','B','(SIN DOCENTE)','403','IQUIQUE','MIERCOLES','18:50','20:30'],
            ['7','101033','Teleinformática II','3','2','4','-','T','A','(SIN DOCENTE)','404','IQUIQUE','JUEVES','15:30','17:10'],
            ['7','101033','Teleinformática II','3','2','-','-','T','A','(SIN DOCENTE)','LAB2','IQUIQUE','JUEVES','17:10','18:50'],
            ['7','101054','Realidad Virtual (Electivo)','3','2','-','-','N','B','(SIN DOCENTE)','403','IQUIQUE','MARTES','18:00','19:40'],
            ['7','101054','Realidad Virtual (Electivo)','3','2','4','-','N','B','(SIN DOCENTE)','LAB2','IQUIQUE','MARTES','19:40','21:20'],
            ['7','101054','Realidad Virtual (Electivo)','3','2','-','-','N','A','(SIN DOCENTE)','404','IQUIQUE','VIERNES','19:40','21:20'],
            ['7','101054','Realidad Virtual (Electivo)','3','2','4','-','N','A','(SIN DOCENTE)','LAB1','IQUIQUE','VIERNES','21:20','23:00'],
            ['8','101034','Finanzas para Empresas','3','2','2','4','N','A','CASAS MIRANDA ROBERTO','LAB-INF-2','IQUIQUE','SABADO','10:30','13:50'],
            ['8','101035','Dinámica de Sist. de Inf.','3','2','2','4','N','A','ESPINOZA SILVERIO EDGAR','LAB-INF-1','IQUIQUE','MIERCOLES','18:50','22:10'],
            ['8','101036','Tópicos Avanzados en Prog.','3','2','2','4','N','A','COLLANTES INGA ZOILA','404','IQUIQUE','VIERNES','18:50','22:10'],
            ['8','101037','Innovación y Tecnología','3','2','2','4','N','A','RODRIGUEZ FIGUEROA JOSE','404','IQUIQUE','LUNES','18:50','22:10'],
            ['8','101038','Sistemas Operativos','3','2','2','4','N','A','MATOS MANGUINURI JEAN','403','IQUIQUE','JUEVES','18:50','22:10'],
            ['8','101039','Proyectos Informáticos','3','2','2','4','N','A','PAREDES VARGAS RONALD','403','IQUIQUE','MARTES','18:50','22:10'], */
        ];

        // Mapeo de Dias
        $diasMap = [
            'LUNES' => 1, 'MARTES' => 2, 'MIERCOLES' => 3, 'JUEVES' => 4, 'VIERNES' => 5, 'SABADO' => 6, 'DOMINGO' => 7
        ];

        foreach ($horariosRaw as $h) {
            // $h[0]: Ciclo
            // $h[1]: Código Curso, $h[9]: Docente, $h[10]: Aula/Lab, $h[11]: Local (Edificio)
            // $h[12]: Dia, $h[13]: Hora Ini, $h[14]: Hora Fin, $h[8]: Sección (A, B)

            $ciclo = (int)$h[0];
            
            // LÓGICA DE PERIODOS: 
            // Ciclos Impares (1,3,5...) -> Periodo 2025-1
            // Ciclos Pares (2,4,6...) -> Periodo 2025-2
            $periodoId = ($ciclo % 2 != 0) ? $periodoImpar->id : $periodoPar->id;

            // 1. PROFESOR
            $nombreDocente = trim($h[9]);
            $profesorId = null;

            if ($nombreDocente !== '(SIN DOCENTE)') {
                $usernameProf = Str::slug($nombreDocente, '_');
                
                $userProf = User::firstOrCreate(
                    ['username' => $usernameProf],
                    ['password' => Hash::make('password'), 'rol' => 'Profesor', 'estado' => 'Activo']
                );

                $partes = explode(' ', $nombreDocente);
                $nombres = array_pop($partes);
                $apellidos = implode(' ', $partes);

                $profesor = Profesor::firstOrCreate(
                    ['usuario_id' => $userProf->id],
                    [
                        'nombres' => $nombres,
                        'apellidos' => $apellidos,
                        'dni' => rand(10000000, 99999999), 
                        'correo_institucional' => $usernameProf . '@unfv.edu.pe',
                        'telefono' => '999999999',
                        'estado' => 'Activo'
                    ]
                );
                $profesorId = $profesor->id;

            } else {
                $userProf = User::firstOrCreate(
                    ['username' => 'por_asignar'], 
                    ['password' => Hash::make('password'), 'rol' => 'Profesor', 'estado' => 'Activo']
                );
                
                $profesor = Profesor::firstOrCreate(
                    ['usuario_id' => $userProf->id],
                    [
                        'nombres' => 'POR', 'apellidos' => 'ASIGNAR', 'dni' => 00000000,
                        'correo_institucional' => 'sin_docente@unfv.edu.pe', 'telefono' => '000000000', 'estado' => 'Activo'
                    ]
                );
                $profesorId = $profesor->id;
            }

            // 2. EDIFICIO Y AULA
            $localName = trim($h[11]);
            $aulaName = trim($h[10]);

            $edificio = Edificio::firstOrCreate(
                ['nombre' => $localName],
                ['facultad_id' => $fiei->id] 
            );

            $aula = Aula::firstOrCreate(
                ['nombre_aula' => $aulaName, 'edificio_id' => $edificio->id],
                ['tipo' => 'Salon Regular', 'capacidad' => 40, 'estado' => 'Activo']
            );

            // 3. ASIGNATURA SECCIÓN
            $asig = Asignatura::where('codigo_asignatura', $h[1])->first();
            
            if ($asig) {
                // Crear Sección vinculada al periodo correcto
                $seccion = AsignaturaSeccion::firstOrCreate([
                    'asignatura_id' => $asig->id,
                    'periodo_id'    => $periodoId, 
                    'nombre_seccion' => $h[8] 
                ], [
                    'profesor_id' => $profesorId,
                    'cupos' => 40,
                    'modalidad' => 'Presencial'
                ]);

                // 4. BLOQUE Y HORARIO
                $horaIni = $h[13] . ':00'; 
                $horaFin = $h[14] . ':00';

                $bloque = BloquesHorarios::firstOrCreate([
                    'hora_inicio' => $horaIni,
                    'hora_fin' => $horaFin
                ]);

                Horarios::firstOrCreate([
                    'asignatura_seccion_id' => $seccion->id,
                    'dia_id' => $diasMap[$h[12]] ?? 1,
                    'bloque_id' => $bloque->id,
                    'aula_id' => $aula->id,
                    'periodo_id' => $periodoId
                ], [
                    'tipo_sesion' => 'Teoría' 
                ]);
            }
        }

        echo ">>> 4.1. CREANDO LOS USUARIOS DEL SISTEMA \n";

        // Admin
        User::create(['username' => 'admin', 'password' => Hash::make('admin'), 'rol' => 'Admin', 'estado' => 'Activo']);

        // Estudiantes
        $alumnos = [
            ['2025010963', 'Geral', 'Lujan', '71717171'],
            ['2022015428', 'Josue', 'Albarracin Rivera', '72389816'],
            ['2021017455', 'Hugo Andre', 'Alfaro Garcia', '70707070']
        ];

        foreach($alumnos as $al) {
            $userEst = User::create(['username' => $al[0], 'password' => Hash::make($al[0]), 'rol' => 'Estudiante', 'estado' => 'Activo']);
            $est = Estudiante::create([
                'usuario_id' => $userEst->id, 'carrera_id' => $carreraInfo->id, 'anio_ingreso' => substr($al[0], 0, 4), 
                'codigo_universitario' => $al[0], 'correo_institucional' => $al[0].'@unfv.edu.pe',
                'nombres' => $al[1], 'apellidos' => $al[2], 'dni' => $al[3], 'estado' => 'Activo'
            ]);
            DetallesEstudiante::create(['estudiante_id' => $est->id, 'estado_matricula' => 'Regular', 'fecha_ingreso' => now(), 'promedio_general' => 0]);
        }

        \Illuminate\Database\Eloquent\Model::reguard();

        echo "\n============================================\n";
        echo ">>> ¡CARGA FINAL COMPLETADA! <<<\n";
        echo "============================================\n";
        echo "Total Cursos Insertados: " . count($cursosData) . "\n";
    }
}