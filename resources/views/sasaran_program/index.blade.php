@extends('layouts.app')
@section('content')
    <h3>Daftar Sasaran Program</h3>
    <a href="{{ route('sasaran-program.create') }}" class="btn btn-primary mb-3">+ Tambah</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>KPI</th>
                <th>Nama Sasaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sasaranPrograms as $item)
                <tr>
                    <td>{{ $item->kpi->judul_kpi ?? '-' }}</td>
                    <td>{{ $item->nama_sasaran }}</td>
                    <td>
                        <a href="{{ route('sasaran-program.edit', $item->id_sasaran) }}"
                            class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('sasaran-program.destroy', $item->id_sasaran) }}" method="POST"
                            class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>



        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Accordion Example</h4>
                        <p class="card-title-desc">Click the accordions below to expand/collapse the accordion content.</p>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Accordion Item #1
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="text-muted">
                                            <strong class="text-dark">This is the first item's accordion body.</strong> It
                                            is hidden by default, until the collapse plugin adds the appropriate classes
                                            that we use to style each element. These classes control the overall appearance,
                                            as well as the showing and hiding via CSS transitions. You can modify any of
                                            this with custom CSS or overriding our default variables. It's also worth noting
                                            that just about any HTML can go within the <code>.accordion-body</code>, though
                                            the transition does limit overflow.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button fw-medium collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                        Accordion Item #2
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="text-muted">
                                            <strong class="text-dark">This is the second item's accordion body.</strong> It
                                            is hidden by default, until the collapse plugin adds the appropriate classes
                                            that we use to style each element. These classes control the overall appearance,
                                            as well as the showing and hiding via CSS transitions. You can modify any of
                                            this with custom CSS or overriding our default variables. It's also worth noting
                                            that just about any HTML can go within the <code>.accordion-body</code>, though
                                            the transition does limit overflow.

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button fw-medium collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                        Accordion Item #3
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="text-muted">
                                            <strong class="text-dark">This is the third item's accordion body.</strong> It
                                            is hidden by default, until the collapse plugin adds the appropriate classes
                                            that we use to style each element. These classes control the overall appearance,
                                            as well as the showing and hiding via CSS transitions. You can modify any of
                                            this with custom CSS or overriding our default variables. It's also worth noting
                                            that just about any HTML can go within the <code>.accordion-body</code>, though
                                            the transition does limit overflow.

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end accordion -->
                    </div>
    </table>
    {{ $sasaranPrograms->links() }}
@endsection
