@extends('layouts.master')

@section('title', 'Matrícula Regular')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="bi bi-journal-check"></i> Proceso de Matrícula Regular</h1>
            <small class="text-muted">Semestre Académico: <strong>2025-I (Régimen Anual)</strong></small>
        </div>
        <div class="text-end">
            <span class="badge bg-primary fs-6">Créditos Máximos: {{ $limites['max'] }}</span>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid pb-5">
    
    {{-- ALERTAS DE ERROR DESDE EL BACKEND --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> Error en la solicitud:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- BARRA DE PROGRESO (STEPPER) --}}
    <div class="row mb-5 justify-content-center">
        <div class="col-md-10">
            <div class="position-relative m-4">
                <div class="progress" style="height: 3px;">
                    <div class="progress-bar bg-warning transition-all" role="progressbar" style="width: 0%;" id="progressBar"></div>
                </div>
                
                {{-- Indicador Paso 1 --}}
                <div class="position-absolute top-0 start-0 translate-middle btn btn-sm btn-primary rounded-pill d-flex align-items-center justify-content-center fw-bold shadow" style="width: 2.5rem; height:2.5rem; border: 3px solid #fff;">1</div>
                <div class="position-absolute top-100 start-0 translate-middle text-primary fw-bold mt-2">Pagos</div>

                {{-- Indicador Paso 2 --}}
                <div class="position-absolute top-0 start-50 translate-middle btn btn-sm btn-secondary rounded-pill d-flex align-items-center justify-content-center fw-bold shadow" style="width: 2.5rem; height:2.5rem; border: 3px solid #fff;" id="step2-indicator">2</div>
                <div class="position-absolute top-100 start-50 translate-middle text-muted mt-2" id="step2-label">Asignaturas</div>

                {{-- Indicador Paso 3 --}}
                <div class="position-absolute top-0 start-100 translate-middle btn btn-sm btn-secondary rounded-pill d-flex align-items-center justify-content-center fw-bold shadow" style="width: 2.5rem; height:2.5rem; border: 3px solid #fff;" id="step3-indicator">3</div>
                <div class="position-absolute top-100 start-100 translate-middle text-muted mt-2" id="step3-label">Finalizar</div>
            </div>
        </div>
    </div>

    {{-- FORMULARIO PRINCIPAL --}}
    <div class="card card-outline card-warning shadow-lg">
        <div class="card-header bg-white border-bottom-0 pt-3">
            <h3 class="card-title fw-bold text-dark" id="card-title">Paso 1: Registro de Pagos</h3>
        </div>
        
        <form id="matriculaForm" action="{{ route('matricula.regular.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">

                {{-- ========================================================== --}}
                {{-- PASO 1: REGISTRO DE PAGOS --}}
                {{-- ========================================================== --}}
                <div id="step1-content">
                    <div class="alert alert-light border-start border-warning border-4 text-dark mb-4 shadow-sm">
                        <div class="d-flex">
                            <div class="me-3 text-warning"><i class="bi bi-info-circle-fill fs-3"></i></div>
                            <div>
                                <strong>Importante:</strong> Para el régimen anual, debes registrar el pago total de la matrícula. 
                                Si realizaste el pago en dos partes (dos vouchers), utiliza la sección "Boleta 2" para adjuntar el segundo.
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        {{-- BOLETA 1 (OBLIGATORIA) --}}
                        <div class="col-md-6 border-end">
                            <h5 class="text-primary fw-bold mb-3 border-bottom pb-2"><i class="bi bi-receipt"></i> Boleta 1 (Obligatorio)</h5>
                            
                            <div class="form-floating mb-3">
                                <select class="form-select" name="boleta1_banco" id="boleta1_banco" required>
                                    <option value="" selected disabled>Seleccione Banco...</option>
                                    <option value="Banco de Comercio" {{ old('boleta1_banco') == 'Banco de Comercio' ? 'selected' : '' }}>Banco de Comercio</option>
                                    <option value="BCP" {{ old('boleta1_banco') == 'BCP' ? 'selected' : '' }}>BCP</option>
                                    <option value="Yape" {{ old('boleta1_banco') == 'Yape' ? 'selected' : '' }}>Yape</option>
                                    <option value="Interbank" {{ old('boleta1_banco') == 'Interbank' ? 'selected' : '' }}>Interbank</option>
                                </select>
                                <label for="boleta1_banco">Entidad Financiera</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="boleta1_codigo" id="boleta1_codigo" placeholder="Código" required value="{{ old('boleta1_codigo') }}">
                                <label for="boleta1_codigo">Código de Liquidación / Operación</label>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text bg-light fw-bold">S/.</span>
                                <div class="form-floating flex-grow-1">
                                    <input type="number" class="form-control" name="boleta1_monto" id="boleta1_monto" step="0.10" placeholder="Monto" required value="{{ old('boleta1_monto') }}">
                                    <label for="boleta1_monto">Monto Pagado</label>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="boleta1_fecha" id="boleta1_fecha" required value="{{ old('boleta1_fecha') }}">
                                <label for="boleta1_fecha">Fecha de Pago</label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Foto del Voucher</label>
                                <input type="file" class="form-control" name="boleta1_foto" accept="image/*" required>
                                <div class="form-text">Asegúrate que la imagen sea nítida (JPG, PNG).</div>
                            </div>
                        </div>

                        {{-- BOLETA 2 (OPCIONAL) --}}
                        <div class="col-md-6">
                            <h5 class="text-secondary fw-bold mb-3 border-bottom pb-2">
                                <i class="bi bi-receipt-cutoff"></i> Boleta 2 (Opcional)
                            </h5>
                            
                            <div class="form-floating mb-3">
                                <select class="form-select" name="boleta2_banco" id="boleta2_banco">
                                    <option value="" selected disabled>Seleccione Banco...</option>
                                    <option value="Banco de Comercio" {{ old('boleta2_banco') == 'Banco de Comercio' ? 'selected' : '' }}>Banco de Comercio</option>
                                    <option value="BCP" {{ old('boleta2_banco') == 'BCP' ? 'selected' : '' }}>BCP</option>
                                    <option value="Yape" {{ old('boleta2_banco') == 'Yape' ? 'selected' : '' }}>Yape</option>
                                    <option value="Interbank" {{ old('boleta2_banco') == 'Interbank' ? 'selected' : '' }}>Interbank</option>
                                </select>
                                <label for="boleta2_banco">Entidad Financiera</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="boleta2_codigo" id="boleta2_codigo" placeholder="Código" value="{{ old('boleta2_codigo') }}">
                                <label for="boleta2_codigo">Código de Liquidación</label>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text bg-light fw-bold">S/.</span>
                                <div class="form-floating flex-grow-1">
                                    <input type="number" class="form-control" name="boleta2_monto" id="boleta2_monto" step="0.10" placeholder="Monto" value="{{ old('boleta2_monto') }}">
                                    <label for="boleta2_monto">Monto Pagado</label>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="boleta2_fecha" id="boleta2_fecha" value="{{ old('boleta2_fecha') }}">
                                <label for="boleta2_fecha">Fecha de Pago</label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Foto del Voucher</label>
                                <input type="file" class="form-control" name="boleta2_foto" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========================================================== --}}
                {{-- PASO 2: SELECCIÓN DE CURSOS (DINÁMICO) --}}
                {{-- ========================================================== --}}
                <div id="step2-content" style="display: none;">
                    
                    <div class="row align-items-center mb-4">
                        <div class="col-md-8">
                            <div class="alert alert-info m-0 shadow-sm border-0">
                                <i class="bi bi-shield-lock-fill me-2"></i>
                                Rango permitido: Mínimo <strong>{{ $limites['min'] }}</strong> - Máximo <strong>{{ $limites['max'] }}</strong> créditos.
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="card bg-light border-0">
                                <div class="card-body py-2">
                                    <small class="text-muted text-uppercase fw-bold">Total Créditos</small>
                                    <h3 class="m-0 fw-bold" id="total-credits-display">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive border rounded shadow-sm" style="max-height: 600px; overflow-y: auto;">
                        <table class="table table-hover table-striped align-middle mb-0">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th class="text-center" style="width: 60px">Elegir</th>
                                    <th class="text-center" style="width: 80px">Ciclo</th>
                                    <th style="width: 100px">Código</th>
                                    <th>Asignatura</th>
                                    <th class="text-center" style="width: 80px">Créd.</th>
                                    <th style="width: 250px">Sección / Turno</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cursosDisponibles as $malla)
                                    @php
                                        // Filtramos si tiene secciones activas (vacantes)
                                        $secciones = $malla->asignatura->secciones; 
                                        $tieneSecciones = $secciones->isNotEmpty();
                                        $esImpar = $malla->semestre % 2 != 0;
                                    @endphp
                                    
                                    <tr class="{{ $tieneSecciones ? '' : 'table-secondary text-muted' }}">
                                        <td class="text-center">
                                            {{-- CHECKBOX: Solo activa lógica JS --}}
                                            <div class="form-check d-flex justify-content-center">
                                                <input type="checkbox" 
                                                       class="form-check-input course-check" 
                                                       style="transform: scale(1.3);"
                                                       data-credits="{{ $malla->asignatura->creditos }}"
                                                       data-course-id="{{ $malla->asignatura->id }}"
                                                       id="chk-{{ $malla->asignatura->id }}"
                                                       {{ !$tieneSecciones ? 'disabled' : '' }}>
                                            </div>
                                        </td>
                                        <td class="text-center fw-bold">
                                            <span class="badge {{ $esImpar ? 'bg-primary' : 'bg-info text-dark' }}">
                                                {{ $malla->semestre }}
                                            </span>
                                        </td>
                                        <td class="font-monospace small">{{ $malla->asignatura->codigo_asignatura }}</td>
                                        <td class="fw-bold">
                                            {{ $malla->asignatura->nombre }}
                                            @if(!$tieneSecciones) 
                                                <span class="badge bg-danger ms-2">No disponible</span> 
                                            @endif
                                        </td>
                                        <td class="text-center fs-5 fw-bold text-secondary">{{ $malla->asignatura->creditos }}</td>
                                        <td>
                                            @if($tieneSecciones)
                                                {{-- SELECT: Envía "cursos[]" con el ID de la sección --}}
                                                <select class="form-select form-select-sm section-select border-primary" 
                                                        name="cursos[]" 
                                                        disabled
                                                        required>
                                                    @foreach ($secciones as $seccion)
                                                        <option value="{{ $seccion->id }}">
                                                            Sección {{ $seccion->nombre_seccion }} ({{ $seccion->modalidad ?? 'P' }}) - {{ $seccion->cupos }} vac.
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <small class="fst-italic">Sin horarios</small>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                No se encontraron cursos disponibles para tu plan de estudios.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="card-footer bg-white d-flex justify-content-between py-3">
                {{-- Botón Atrás --}}
                <button type="button" class="btn btn-outline-secondary px-4 fw-bold" id="btn-prev" style="display: none;">
                    <i class="bi bi-arrow-left me-2"></i> Volver a Pagos
                </button>
                
                {{-- Botón Continuar --}}
                <button type="button" class="btn btn-warning px-4 fw-bold text-white shadow-sm" id="btn-next">
                    Validar y Seleccionar Cursos <i class="bi bi-arrow-right ms-2"></i>
                </button>

                {{-- Botón Finalizar --}}
                <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm" id="btn-submit" style="display: none;">
                    <i class="bi bi-check-circle-fill me-2"></i> Confirmar Matrícula Anual
                </button>
            </div>
        </form>
    </div>
</div>
@stop

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- CONSTANTES Y VARIABLES ---
        const MAX_CREDITS = {{ $limites['max'] }};
        const MIN_CREDITS = {{ $limites['min'] }};
        
        // Elementos DOM
        const step1 = document.getElementById('step1-content');
        const step2 = document.getElementById('step2-content');
        const btnNext = document.getElementById('btn-next');
        const btnPrev = document.getElementById('btn-prev');
        const btnSubmit = document.getElementById('btn-submit');
        
        const totalCreditsDisplay = document.getElementById('total-credits-display');
        const progressBar = document.getElementById('progressBar');
        const step2Indicator = document.getElementById('step2-indicator');
        const step2Label = document.getElementById('step2-label');
        const step3Indicator = document.getElementById('step3-indicator');
        const step3Label = document.getElementById('step3-label');
        const cardTitle = document.getElementById('card-title');
        
        const checkboxes = document.querySelectorAll('.course-check');

        // --- LÓGICA DE NAVEGACIÓN ---

        // Ir al Paso 2 (Cursos)
        btnNext.addEventListener('click', function() {
            // Validar HTML5 required del Paso 1
            const inputsRequeridos = step1.querySelectorAll('input[required], select[required]');
            let formValido = true;
            
            inputsRequeridos.forEach(input => {
                if (!input.value) {
                    formValido = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!formValido) {
                // Usar SweetAlert si lo tienes, sino alert normal
                alert('Por favor complete todos los datos obligatorios de la Boleta 1.');
                return;
            }

            // Transición
            step1.style.display = 'none';
            step2.style.display = 'block';
            
            btnNext.style.display = 'none';
            btnPrev.style.display = 'block';
            btnSubmit.style.display = 'block';
            
            // Actualizar UI Stepper
            progressBar.style.width = '50%';
            step2Indicator.classList.remove('btn-secondary');
            step2Indicator.classList.add('btn-primary');
            step2Label.classList.add('text-primary', 'fw-bold');
            
            cardTitle.innerText = 'Paso 2: Selección de Asignaturas';
            window.scrollTo(0, 0);
        });

        // Volver al Paso 1 (Pagos)
        btnPrev.addEventListener('click', function() {
            step1.style.display = 'block';
            step2.style.display = 'none';
            
            btnNext.style.display = 'block';
            btnPrev.style.display = 'none';
            btnSubmit.style.display = 'none';
            
            progressBar.style.width = '0%';
            step2Indicator.classList.remove('btn-primary');
            step2Indicator.classList.add('btn-secondary');
            step2Label.classList.remove('text-primary', 'fw-bold');
            step2Label.classList.add('text-muted');
            
            cardTitle.innerText = 'Paso 1: Registro de Pagos';
        });

        // --- LÓGICA DE SELECCIÓN DE CURSOS ---

        checkboxes.forEach(chk => {
            chk.addEventListener('change', function(e) {
                const row = this.closest('tr');
                const select = row.querySelector('.section-select');
                const credits = parseFloat(this.getAttribute('data-credits'));

                // 1. Activar/Desactivar Select
                if (this.checked) {
                    select.disabled = false;
                    row.classList.add('table-warning'); // Highlight visual
                } else {
                    select.disabled = true;
                    row.classList.remove('table-warning');
                }

                // 2. Calcular Nuevo Total
                let currentTotal = calculateTotal();

                // 3. Validar Tope Máximo (50)
                if (currentTotal > MAX_CREDITS) {
                    alert(`¡Alto! Has superado el límite de ${MAX_CREDITS} créditos anuales.`);
                    
                    // Revertir cambio
                    this.checked = false;
                    select.disabled = true;
                    row.classList.remove('table-warning');
                    
                    // Recalcular para mostrar el valor correcto
                    currentTotal = calculateTotal();
                }

                // 4. Actualizar Vista del Contador
                updateDisplay(currentTotal);
            });
        });

        function calculateTotal() {
            let sum = 0;
            checkboxes.forEach(c => {
                if(c.checked) {
                    sum += parseFloat(c.getAttribute('data-credits'));
                }
            });
            return sum;
        }

        function updateDisplay(total) {
            totalCreditsDisplay.innerText = total;

            // Colores según estado
            totalCreditsDisplay.className = 'm-0 fw-bold'; // Reset clases
            
            if (total < MIN_CREDITS) {
                totalCreditsDisplay.classList.add('text-danger'); // Menos del mínimo
            } else if (total > MAX_CREDITS) {
                totalCreditsDisplay.classList.add('text-danger'); // Excedido (aunque el if anterior lo previene)
            } else {
                totalCreditsDisplay.classList.add('text-success'); // OK
            }
        }

        // --- VALIDACIÓN FINAL AL ENVIAR ---
        
        btnSubmit.addEventListener('click', function(e) {
            let total = calculateTotal();
            
            if (total < MIN_CREDITS) {
                e.preventDefault(); // Detener envío
                alert(`Error: Debes matricularte en un mínimo de ${MIN_CREDITS} créditos. Actualmente tienes ${total}.`);
                return;
            }

            // Confirmación simple
            if(!confirm(`Estás a punto de matricularte en ${total} créditos. ¿Deseas continuar?`)) {
                e.preventDefault();
                return;
            }

            // Actualizar barra al 100% visualmente
            progressBar.style.width = '100%';
            step3Indicator.classList.remove('btn-secondary');
            step3Indicator.classList.add('btn-success');
        });
    });
</script>
@endpush