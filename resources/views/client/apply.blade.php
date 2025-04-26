<!DOCTYPE html>
<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('client.common.head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
  .disability-container {
    display: inline-block;
    align-items: center;
    /* Align vertically */
    gap: 10px;
    /* Space between checkbox and text */
    margin-bottom: 10px;
    /* Add spacing between each checkbox */
  }

  .applyf {
    width: 90%;
    max-width: 900px;
    background: #FFFFFF;
    /* White background for the form */
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }

  .tabs {
    display: flex;
    border-bottom: 2px solid #7AB2B2;
    /* Teal color for the border */
    background-color: #CDE8E5;
    /* Light teal background */
  }

  h2 {
    color: #3674B5;
  }

  .tab-button {
    flex: 1;
    background: #CDE8E5;
    border: none;
    padding: 15px;
    text-align: center;
    cursor: pointer;
    transition: background 0.3s, color 0.3s, border-bottom 0.3s;
    font-size: 16px;
    color: #3674B5;
    /* Dark blue text */
  }

  .tab-button:hover {
    background: #B0D9D6;
    /* Slightly darker teal */
  }

  .tab-button.active {
    background: #FFFFFF;
    /* White background for the active tab */
    color: #3674B5;
    /* Dark blue text for the active tab */
    border-bottom: 2px solid #3674B5;
    /* Dark blue bottom border */
  }

  .tab-content {
    padding: 20px;
    background: #FFFFFF;
    /* White background for content */
    animation: fadeIn 0.3s ease-in-out;
  }

  .tab-panel {
    display: none;
  }

  .tab-panel.active {
    display: block;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
    }

    to {
      opacity: 1;
    }
  }

  form {
    display: flex;
    flex-direction: column;
  }

  label {
    font-weight: bold;
    margin-bottom: 5px;
    color: #3674B5;
    /* Dark blue text for labels */
  }

  input,
  select,
  textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #7AB2B2;
    /* Teal border for input fields */
    border-radius: 4px;
    font-size: 16px;
  }

  input:focus,
  select:focus,
  textarea:focus {
    border-color: #3674B5;
    /* Dark blue border on focus */
    outline: none;
  }

  textarea {
    resize: vertical;
    /* Allow vertical resizing only */
  }

  /* New styles for the submit button */
  button[type="submit"] {
    background-color: #3674B5;
    /* Dark blue background */
    color: #FFFFFF;
    /* White text */
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s;
    float: right;
  }

  button[type="submit"]:hover {
    background-color: #3a6f84;
    /* Slightly darker blue */
    transform: scale(1.05);
    /* Slight scale effect on hover */
  }

  button[type="submit"]:focus {
    outline: none;
    box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
    /* Light shadow on focus */
  }

  /* New styles for the file input */
  input[type="file"] {
    padding: 0;
    margin: 0;
    border: 1px solid #7AB2B2;
    /* Teal border for file input */
    border-radius: 4px;
    background-color: #FFFFFF;
    /* White background */
    color: #3674B5;
    /* Dark blue text for file input */
    font-size: 16px;
    cursor: pointer;
  }

  input[type="file"]::file-selector-button {
    background-color: #3674B5;
    /* Dark blue button background */
    color: #FFFFFF;
    /* White text */
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s;
  }

  input[type="file"]::file-selector-button:hover {
    background-color: #3a6f84;
    /* Slightly darker blue */
    transform: scale(1.05);
    /* Slight scale effect on hover */
  }

  /* New styles for the navigation buttons */
  button.next-button,
  button.prev-button {
    background-color: #3674B5;
    /* Dark blue background */
    color: #FFFFFF;
    /* White text */
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s;
    margin: 5px;
  }

  button.next-button:hover,
  button.prev-button:hover {
    background-color: #3a6f84;
    /* Slightly darker blue */
    transform: scale(1.05);
    /* Slight scale effect on hover */
  }

  button.next-button:focus,
  button.prev-button:focus {
    outline: none;
    box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
    /* Light shadow on focus */
  }

  .header .branding {
    background-color: #3674B5 !important;
  }
</style>

<body class="index-page">

  <main class="main">

    <!-- Hero Section -->
    <section id="apply" class="apply section accent-background">

      @include('client.common.navbar')


    </section><!-- /Hero Section -->
    @if(!$academicYear)
    <div class="container-fluid px-4"><br>
      <p class="mt-1" style="color:#729762;text-align:center;text-shadow:2px 2px 5px white;">
        <button data-text="Awesome" class="buttonpma">
          <span class="actual-text">&nbsp;Application&nbsp;Period&nbsp;Closed&nbsp;</span>
          <span class="hover-text" aria-hidden="true">&nbsp;Application&nbsp;Period&nbsp;Closed&nbsp;</span>
        </button>
      </p>
      <p class="description" style="text-align:center">The registration and application period for this academic year has ended. See you next year!</p>
    </div>
    @else
    <div class="container-fluid px-4"><br>
      <p class="mt-1" style="color:#729762;text-align:center;text-shadow:2px 2px 5px white;">
        <button data-text="Awesome" class="buttonpma">
          <span class="actual-text">&nbsp;Apply&nbsp;Now&nbsp;</span>
          <span class="hover-text" aria-hidden="true">&nbsp;Apply&nbsp;Now&nbsp;</span>
        </button>
      </p>
    </div><br>
    <!-- Application Form Section -->

    <!-- Application Form Section -->

    <div class="applyf container-fluid">
      <div class="tabs">
        <button class="tab-button active" onclick="openTab('tab1')">Personal Information</button>
        <button class="tab-button" onclick="openTab('tab2')">Contact Details</button>
        <button class="tab-button" onclick="openTab('tab3')">Upload Pre-Certificate</button>
      </div>

      <div class="tab-content">
        <form id="application-form" action="{{ route('application.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="academic_year_id" value="{{ $academicYear->id }}">
          <h2>Applying for Academic Year: {{ $academicYear->name }}</h2>
          <!-- Tab 1: Personal Information -->
          <div id="tab1" class="tab-panel active">
            <h2>Personal Information</h2>

            <!-- First Name -->
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required><br><br>

            <!-- Last Name -->
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" required><br><br>

            <!-- Date of Birth -->
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required><br><br>

            <!-- Gender -->
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
              <option value="male">Male</option>
              <option value="female">Female</option>

            </select><br><br>

            <!-- Disabilities -->
            <label for="disabilities">Disability Types:</label><br>
            @foreach($disabilities as $disability)
            <div class="disability-container">
              <input type="checkbox" id="disability_{{ $disability->id }}" name="disabilities[]" value="{{ $disability->id }}">
              <label for="disability_{{ $disability->id }}">{{ $disability->name }}</label>
            </div>
            @endforeach
            <br>

            <!-- Profile Picture -->
            <label for="profile_img">Profile Picture:</label>
            <input type="file" id="profile_img" name="profile_img" accept=".jpg, .jpeg, .png"><br><br>

            <button type="button" class="next-button" onclick="nextTab()">Next</button>
          </div>

          <!-- Tab 2: Contact Details -->
          <div id="tab2" class="tab-panel">
            <h2>Contact Details</h2>

            <!-- Parent's Phone Number -->
            <label for="parents_contact_numbers">Parent's Phone Number:</label>
            <input type="tel" id="parents_contact_numbers" name="parents_contact_numbers" placeholder="+1234567890" required><br><br>

            <!-- Address -->
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" placeholder="123 Main St, City, State, ZIP" required><br><br>

            <!-- Parent's Name -->
            <label for="parents_names">Parent's Name:</label>
            <input type="text" id="parents_names" name="parents_names" placeholder="Enter parent's name" required><br><br>

            <button type="button" class="prev-button" onclick="prevTab()">Previous</button>
            <button type="button" class="next-button" onclick="nextTab()">Next</button>
          </div>

          <!-- Tab 3: Upload Pre-Certificate -->
          <div id="tab3" class="tab-panel">
            <h2>Upload Pre-Certificate</h2>

            <!-- Pre-Certificate Upload -->
            <label for="precertificate">Upload your Pre-Certificate:</label>
            <input type="file" id="precertificate" name="precertificate" accept=".pdf, .doc, .docx" required><br><br>

            <!-- Grade Selection -->
            <label for="grade_id">Intended Class:</label>
            <select id="grade_id" name="grade_id" required>
              <option value="" disabled selected>Select a Class</option>
              @foreach($grades as $grade)
              <option value="{{ $grade->id }}">{{ $grade->name }}</option>
              @endforeach
            </select><br><br>

            <!-- ID Card Image -->
            <label for="id_card_img">ID Card Image:</label>
            <input type="file" id="id_card_img" name="id_card_img" accept=".jpg, .jpeg, .png"><br><br>

            <!-- Submit Button -->
            <button type="submit" class="applybtn">
              Submit
              <svg fill="currentColor" viewBox="0 0 24 24" class="icon">
                <path clip-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm4.28 10.28a.75.75 0 000-1.06l-3-3a.75.75 0 10-1.06 1.06l1.72 1.72H8.25a.75.75 0 000 1.5h5.69l-1.72 1.72a.75.75 0 101.06 1.06l3-3z" fill-rule="evenodd"></path>
              </svg>
            </button>

            <button type="button" class="prev-button" onclick="prevTab()">Previous</button>
          </div>
        </form>
      </div>
    </div>
    @endif

    <!-- End Application Form Section -->

  </main><br>
  @include('client.common.footer')
  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>
  @include('client.common.scripts')

</body>

</html>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Initialize the first tab as active
    openTab('tab1');
  });

  function openTab(tabId) {
    // Get all tab panels and buttons
    const tabs = document.querySelectorAll('.tab-panel');
    const buttons = document.querySelectorAll('.tab-button');

    // Hide all tab panels and remove active class from all buttons
    tabs.forEach(tab => tab.classList.remove('active'));
    buttons.forEach(button => button.classList.remove('active'));

    // Show the selected tab panel and set the corresponding button as active
    document.getElementById(tabId).classList.add('active');
    document.querySelector(`.tab-button[onclick="openTab('${tabId}')"]`).classList.add('active');
  }

  function nextTab() {
    const activeTab = document.querySelector('.tab-panel.active');
    const tabs = Array.from(document.querySelectorAll('.tab-panel'));
    const currentIndex = tabs.indexOf(activeTab);
    if (currentIndex < tabs.length - 1) {
      const nextTabId = tabs[currentIndex + 1].id;
      openTab(nextTabId);
    }
  }

  function prevTab() {
    const activeTab = document.querySelector('.tab-panel.active');
    const tabs = Array.from(document.querySelectorAll('.tab-panel'));
    const currentIndex = tabs.indexOf(activeTab);
    if (currentIndex > 0) {
      const prevTabId = tabs[currentIndex - 1].id;
      openTab(prevTabId);
    }
  }
  document.getElementById('application-form').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent the form from submitting the default way

    // Ensure all required fields are validated
    const form = e.target;
    const inputs = form.querySelectorAll('input[required], select[required]');
    let formIsValid = true;

    inputs.forEach(input => {
      if (!input.checkValidity()) {
        formIsValid = false;
        input.classList.add('invalid'); // Add a class for styling if needed
      } else {
        input.classList.remove('invalid');
      }
    });

    if (!formIsValid) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Please fill out all required fields correctly.',
        background: '#3674B5',
        color: 'white',
        confirmButtonColor: '#3b6d7d',
        confirmButtonText: 'OK'
      });
      return;
    }

    const formData = new FormData(form);

    // Get CSRF token from the page
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Add CSRF token to the form data manually
    formData.append('_token', csrfToken);

    fetch("{{ route('application.store') }}", { // Use the Laravel route helper here
        method: 'POST',
        body: formData,
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken // Add CSRF token in headers
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Handle success
          console.log('Success:', data);
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: data.message,
            background: '#3674B5',
            color: 'white',
            confirmButtonColor: '#3b6d7d',
            timer: 2000, // Auto-close after 2 seconds
            willClose: () => {
              window.location.href = '/one-step-left'; // Redirect to the applications index after the alert is closed
            }
          });
        } else {
          // Handle error
          console.error('Error:', data);
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'There was an error submitting the form.',
            background: '#3674B5',
            color: 'white',
            confirmButtonColor: '#3b6d7d',
            confirmButtonText: 'OK'
          });
        }
      })
      .catch(error => {
        // Handle error
        console.error('Error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: 'There was an error submitting the form.',
          background: '#3674B5',
          color: 'white',
          confirmButtonColor: '#3b6d7d',
          confirmButtonText: 'OK'
        });
      });
  });
</script>