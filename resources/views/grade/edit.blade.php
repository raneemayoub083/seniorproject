<x-layouts.app>
    <div class="container">
        <p class="mt-4" style="color:#729762;text-align:center;text-shadow:2px 2px 5px white;">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;Edit&nbsp;Grade&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;Edit&nbsp;Grade&nbsp;</span>
            </button>
        </p>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('grade.update', ['id' => $grade->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Grade Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $grade->name }}">
                                @error('name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="subjects">Subjects</label>
                                <div class="form-check">
                                    @foreach($subjects as $subject)
                                    <div class="form-check">
                                        <label class="checkbox-btn">
                                            <input class="form-check-input" type="checkbox" name="subjects[]" value="{{ $subject->id }}" id="subject-{{ $subject->id }}" {{ $grade->subjects->contains($subject->id) ? 'checked' : '' }}>
                                            <span class="checkmark"></span>
                                            <label class="form-check-label" for="subject-{{ $subject->id }}">
                                                {{ $subject->name }}
                                            </label>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @error('subjects')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
<style>
    /* From Uiverse.io by boryanakrasteva */
    /* Customize the label (the checkbox-btn) */
    .checkbox-btn {
        display: block;
        position: relative;
        padding-left: 30px;
        margin-bottom: 10px;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default checkbox */
    .checkbox-btn input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkbox-btn label {
        cursor: pointer;
        font-size: 14px;
    }

    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        border: 2.5px solid #3674B5;
        transition: .2s linear;
    }

    .checkbox-btn input:checked~.checkmark {
        background-color: #3674B5;
        color: white;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        visibility: hidden;
        opacity: 0;
        left: 50%;
        top: 40%;
        width: 10px;
        height: 14px;
        border: 2px solid white;
        filter: drop-shadow(0px 0px 10px #0ea021);
        border-width: 0 2.5px 2.5px 0;
        transition: .2s linear;
        transform: translate(-50%, -50%) rotate(-90deg) scale(0.2);
    }

    /* Show the checkmark when checked */
    .checkbox-btn input:checked~.checkmark:after {
        visibility: visible;
        opacity: 1;
        transform: translate(-50%, -50%) rotate(0deg) scale(1);
        animation: pulse 1s ease-in;
    }

    .checkbox-btn input:checked~.checkmark {
        transform: rotate(45deg);
        border: none;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: translate(-50%, -50%) rotate(0deg) scale(1);
        }

        50% {
            transform: translate(-50%, -50%) rotate(0deg) scale(1.6);
        }
    }
</style>