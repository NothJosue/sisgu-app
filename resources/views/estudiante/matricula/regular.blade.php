@extends('layouts.master')

@section('title', 'Matrícula Regular')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Proceso de Matrícula Regular</h1>
        <small class="text-muted">Semestre 2025-I</small>
    </div>
@stop

@section('content')
<div class="container-fluid">
    
    {{-- ALERTAS DE ERROR SI FALLA LA VALIDACIÓN --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="bi bi-exclamation-triangle-fill"></i> Error en el formulario:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- BARRA DE PROGRESO (STEPPER) - SE MANTIENE IGUAL --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="position-relative m-4">
                <div class="progress" style="height: 2px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 0%;" id="progressBar"></div>
                </div>
                <div class="position-absolute top-0 start-0 translate-middle btn btn-sm btn-primary rounded-pill" style="width: 2rem; height:2rem;">1</div>
                <div class="position-absolute top-0 start-50 translate-middle btn btn-sm btn-secondary rounded-pill" style="width: 2rem; height:2rem;" id="step2-indicator">2</div>
                <div class="position-absolute top-0 start-100 translate-middle btn btn-sm btn-secondary rounded-pill" style="width: 2rem; height:2rem;" id="step3-indicator">3</div>
                
                <div class="position-absolute top-100 start-0 translate-middle text-primary fw-bold mt-2">Pagos</div>
                <div class="position-absolute top-100 start-50 translate-middle text-muted mt-2" id="step2-label">Cursos</div>
                <div class="position-absolute top-100 start-100 translate-middle text-muted mt-2" id="step3-label">Finalizar</div>
            </div>
        </div>
    </div>

    {{-- CARD PRINCIPAL --}}
    <div class="card card-outline card-warning shadow-sm mt-5">
        <div class="card-header">
            <h3 class="card-title" id="card-title">Paso 1: Registro de Pagos</h3>
        </div>
        
        {{-- FORMULARIO CONECTADO AL BACKEND --}}
        <form id="matriculaForm" action="{{ route('matricula.regular.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">

                {{-- ========================================================== --}}
                {{-- PASO 1: LOS PAGOS (BOLETA 1 Y 2) --}}
                {{-- ========================================================== --}}
                <div id="step1-content">
                    <div class="row">
                        {{-- BOLETA 1 (Izquierda) --}}
                        <div class="col-md-6 border-end">
                            <h5 class="text-primary mb-3"><i class="bi bi-receipt"></i> Boleta 1 (Matrícula)</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Entidad Financiera</label>
                                <select class="form-select" name="boleta1_banco" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Banco de Comercio">Banco de Comercio</option>
                                    <option value="BCP">BCP</option>
                                    <option value="Yape">Yape</option>
                                    <option value="Interbank">Interbank</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Código de Liquidación / Operación</label>
                                <input type="text" class="form-control" name="boleta1_codigo" placeholder="Ej: C65SDX3ZY" required value="{{ old('boleta1_codigo') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Monto (S/.)</label>
                                <div class="input-group">
                                    <span class="input-group-text">S/.</span>
                                    <input type="number" class="form-control" name="boleta1_monto" step="0.10" required value="{{ old('boleta1_monto') }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Fecha de Pago</label>
                                <input type="date" class="form-control" name="boleta1_fecha" required value="{{ old('boleta1_fecha') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Foto del Voucher</label>
                                <input type="file" class="form-control" name="boleta1_foto" accept="image/*" required>
                                <div class="form-text">Subir imagen nítida (JPG/PNG).</div>
                            </div>
                        </div>

                        {{-- BOLETA 2 (Derecha) - Opcional según lógica, pero preparada --}}
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3"><i class="bi bi-receipt-cutoff"></i> Boleta 2 (Opcional)</h5>
                            <small class="text-muted d-block mb-2">Llenar solo si realizó el pago en dos partes.</small>
                            
                            <div class="mb-3">
                                <label class="form-label">Entidad Financiera</label>
                                <select class="form-select" name="boleta2_banco">
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Banco de Comercio">Banco de Comercio</option>
                                    <option value="BCP">BCP</option>
                                    <option value="Yape">Yape</option>
                                    <option value="Interbank">Interbank</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Código de Liquidación</label>
                                <input type="text" class="form-control" name="boleta2_codigo" placeholder="Ej: C65SDX3ZY" value="{{ old('boleta2_codigo') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Monto (S/.)</label>
                                <div class="input-group">
                                    <span class="input-group-text">S/.</span>
                                    <input type="number" class="form-control" name="boleta2_monto" step="0.10" value="{{ old('boleta2_monto') }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Fecha de Pago</label>
                                <input type="date" class="form-control" name="boleta2_fecha" value="{{ old('boleta2_fecha') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Foto del Voucher</label>
                                <input type="file" class="form-control" name="boleta2_foto" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PASO 2: SELECCIÓN DE CURSOS (Mantenemos tu lógica visual) --}}
                <div id="step2-content" style="display: none;">
                    {{-- ... Tu tabla de cursos se mantiene igual ... --}}
                    {{-- Por ahora no enviamos cursos al backend, solo simulamos --}}
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle"></i> En esta etapa solo se procesarán los pagos. Los cursos se guardarán en la siguiente fase.
                    </div>
                </div>

            </div>

            <div class="card-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" id="btn-prev" style="display: none;">
                    <i class="bi bi-arrow-left"></i> Atrás
                </button>
                
                <button type="button" class="btn btn-warning fw-bold text-white" id="btn-next">
                    Validar y Continuar <i class="bi bi-arrow-right"></i>
                </button>

                <button type="submit" class="btn btn-success fw-bold" id="btn-submit" style="display: none;">
                    <i class="bi bi-check-circle-fill"></i> Finalizar Matrícula
                </button>
            </div>
        </form>
    </div>
</div>
@stop

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables de control
        const step1Content = document.getElementById('step1-content');
        const step2Content = document.getElementById('step2-content');
        
        const btnNext = document.getElementById('btn-next');
        const btnPrev = document.getElementById('btn-prev');
        const btnSubmit = document.getElementById('btn-submit');
        
        const progressBar = document.getElementById('progressBar');
        const step2Indicator = document.getElementById('step2-indicator');
        const step2Label = document.getElementById('step2-label');
        const cardTitle = document.getElementById('card-title');

        // Lógica del botón "Validar y Continuar"
        btnNext.addEventListener('click', function() {
            // AQUÍ PUEDES AGREGAR VALIDACIONES DE JS SI LOS CAMPOS ESTÁN VACÍOS
            // Por ahora simulamos que todo está OK
            
            // 1. Ocultar paso 1, mostrar paso 2
            step1Content.style.display = 'none';
            step2Content.style.display = 'block';
            
            // 2. Actualizar botones
            btnNext.style.display = 'none';
            btnPrev.style.display = 'block';
            btnSubmit.style.display = 'block';
            
            // 3. Actualizar barra de progreso
            progressBar.style.width = '50%';
            step2Indicator.classList.remove('btn-secondary');
            step2Indicator.classList.add('btn-primary');
            step2Label.classList.remove('text-muted');
            step2Label.classList.add('text-primary', 'fw-bold');
            
            // 4. Cambiar título
            cardTitle.innerText = 'Paso 2: Selección de Cursos';
        });

        // Lógica del botón "Atrás"
        btnPrev.addEventListener('click', function() {
            step1Content.style.display = 'block';
            step2Content.style.display = 'none';
            
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

        // Lógica simple para sumar créditos
        const checkboxes = document.querySelectorAll('.course-check');
        const totalCreditsSpan = document.getElementById('total-credits');
        
        checkboxes.forEach(chk => {
            chk.addEventListener('change', function() {
                let sum = 0;
                checkboxes.forEach(c => {
                    if(c.checked) sum += parseFloat(c.getAttribute('data-credits'));
                });
                totalCreditsSpan.innerText = sum;
                
                if(sum > 22) {
                    alert('Has excedido el límite de créditos (22).');
                    this.checked = false;
                    totalCreditsSpan.innerText = sum - parseFloat(this.getAttribute('data-credits'));
                }
            });
        });
    });
</script>
@endpush