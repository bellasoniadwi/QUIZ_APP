@extends('layouts.student')
@section('title','Exams')
@section('content')
<style type="text/css">
    .question_options>li{
        list-style: none;
        height: 40px;
        line-height: 40px;
    }
</style>
    <!-- /.content-header -->
     <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Exams</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Exam</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->

      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <!-- Default box -->
              <div class="card">
                
                <div class="card-body">
                   <div class="row">
                       <div class="col-sm-4">
                          <h3 class="text-center">Time : {{ $exam->exam_duration}} min</h3>
                       </div>
                       <div class="col-sm-4">
                           <h3><b>Timer</b> :  <span class="js-timeout" id="timer">{{ $exam['exam_duration']}}:00</span></h3>
                       </div>
                       
                        <div class="col-sm-4">
                            <h3 class="text-right"><b>Status</b> :Running</h3>
                        </div>
                   </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
              <div class="card mt-4">
                
                <div class="card-body">

                  <form action="{{url('student/submit_questions')}}" method="POST" id="examForm">
                    <input type="hidden" name="exam_id" value="{{ Request::segment(3)}}">
                    {{ csrf_field()}}
                   <div class="row">
                      <div class="col-sm-12">

                        @foreach ($question as $key=>$q)
                            <div class="col-sm-12 mt-4">
                              <p>{{$key+1}}. {{ $q->questions}}</p>
                              <?php 
                                    $options = json_decode(json_decode(json_encode($q->options)),true);
                              ?>
                              <input type="hidden" name="question{{$key+1}}" value="{{$q['id']}}">
                              @if (($q->ans) != null)
                              <ul class="question_options">
                                  <li><input type="radio" value="{{ $options['option1']}}" name="ans{{$key+1}}" @if(old('ans'.$key+1) == $options['option1']) checked @endif> {{ $options['option1']}}</li>
                                  <li><input type="radio" value="{{ $options['option2']}}" name="ans{{$key+1}}" @if(old('ans'.$key+1) == $options['option2']) checked @endif> {{ $options['option2']}}</li>
                                  <li><input type="radio" value="{{ $options['option3']}}" name="ans{{$key+1}}" @if(old('ans'.$key+1) == $options['option3']) checked @endif> {{ $options['option3']}}</li>
                                  <li><input type="radio" value="{{ $options['option4']}}" name="ans{{$key+1}}" @if(old('ans'.$key+1) == $options['option4']) checked @endif> {{ $options['option4']}}</li>

                                  <li style="display: none;"><input value="0" type="radio" checked="checked" name="ans{{$key+1}}"> {{ $options['option4']}}</li>
                              </ul>
                              @else
                              <input type="text" required="required" name="ans{{$key+1}}" placeholder="Isikan jawaban anda..." class="form-control" value="{{ old('ans'.$key+1) }}">
                              @endif
                            </div>
                        @endforeach
                        
                        

                          <div class="col-sm-12">
                            <input type="hidden" name="index" value="{{ $key+1}}">
                              <button type="submit" class="btn btn-primary" id="myCheck">Submit</button>
                          </div>
                      </div>
                   </div>
                  </form>
                  
                </div>
                <!-- /.card-body -->
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <!-- /.content-header -->

    <!-- Modal -->

    <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Jika data tersimpan di localStorage, isi kembali nilai input pada form
      if (localStorage.getItem("examDraft")) {
        const examDraft = JSON.parse(localStorage.getItem("examDraft"));
        const form = document.getElementById("examForm");
        Object.keys(examDraft).forEach(function(key) {
          const input = form.querySelector(`[name="${key}"]`);
          if (input.type === "radio") {
            // Jika input adalah radio button
            const radioButtons = form.querySelectorAll(`[name="${key}"]`);
            radioButtons.forEach(function(radioButton) {
              if (radioButton.value === examDraft[key]) {
                radioButton.checked = true;
              }
            });
          } else {
            // Jika input bukan radio button
            if (input) {
              input.value = examDraft[key];
            }
          }
        });
      }

      // Simpan nilai input pada form ke localStorage setiap 3 detik
      setInterval(function() {
        const form = document.getElementById("examForm");
        const formData = new FormData(form);
        const examDraft = {};
        for (let pair of formData.entries()) {
          examDraft[pair[0]] = pair[1];
        }
        localStorage.setItem("examDraft", JSON.stringify(examDraft));
      }, 3000);
    });
    </script>
@endsection
