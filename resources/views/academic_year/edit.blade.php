<x-layouts.app>
    <div class="container">
        <p class="mt-4" style="color:#729762;text-align:center;text-shadow:2px 2px 5px white;">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;Edit&nbsp;Academic&nbsp;Year&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;Edit&nbsp;Academic&nbsp;Year&nbsp;</span>
            </button>
        </p>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('academic_year.update', ['id' => $academicYear->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Academic Year Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $academicYear->name }}">
                                @error('name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $academicYear->start_date }}">
                                        @error('start_date')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">End Date</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $academicYear->end_date }}">
                                        @error('end_date')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="application_opening">Application Opening Date</label>
                                        <input type="date" name="application_opening" id="application_opening" class="form-control" value="{{ $academicYear->application_opening }}">
                                        @error('application_opening')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="application_expiry">Application Expiry Date</label>
                                        <input type="date" name="application_expiry" id="application_expiry" class="form-control" value="{{ $academicYear->application_expiry }}">
                                        @error('application_expiry')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="pending" {{ $academicYear->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="opened" {{ $academicYear->status == 'opened' ? 'selected' : '' }}>Opened</option>
                                    <option value="completed" {{ $academicYear->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                @error('status')
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