<x-layouts.app>
    <div class="container">
        <p class="mt-4" style="color:#729762;text-align:center;text-shadow:2px 2px 5px white;">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;Create&nbsp;Teacher&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;Create&nbsp;Teacher&nbsp;</span>
            </button>
        </p>
        <form action="{{ route('teacher.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Personal Email (to receive credentials)</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="profile_img">Profile Image</label>
                        <input type="file" name="profile_img" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cv">CV</label>
                        <input type="file" name="cv" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="academic_year_id">Academic Year</label>
                <select name="academic_year_id" class="form-control" required>
                    <option disabled selected>Select Academic Year</option>
                    @foreach($academicYears as $academicYear)
                    <option value="{{ $academicYear->id }}" {{ old('academic_year_id') == $academicYear->id ? 'selected' : '' }}>
                        {{ $academicYear->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="subjects">Subjects</label>
                <div class="form-check">
                    <div class="checkbox-container">
                        @foreach($subjects as $subject)
                        <div class="form-check me-4">
                            <label class="checkbox-btn">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="subjects[]"
                                    value="{{ $subject->id }}"
                                    id="subject-{{ $subject->id }}"
                                    {{ is_array(old('subjects')) && in_array($subject->id, old('subjects')) ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                                <label class="form-check-label" for="subject-{{ $subject->id }}">
                                    {{ $subject->name }}
                                </label>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Add Teacher</button>
        </form>

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