
  const params = new URLSearchParams(window.location.search);

  if (params.get('erreur') === '1') {
    document.getElementById('msg-erreur').style.display = 'block';
  }
    if (params.get('error') === 'carun') {
    document.getElementById('msg-erreur-car').style.display = 'block';
  }
    if (params.get('erreur') === '2') {
    document.getElementById('msg-erreur-email').style.display = 'block';
  }
   if (params.get('verified') === '1') {
    document.getElementById('msg-email-valide').style.display = 'block';
  }


      const loginTab = document.getElementById("login-tab");
      const registerTab = document.getElementById("register-tab");
      const loginForm = document.getElementById("login-form");
      const registerForm = document.getElementById("register-form");

        loginTab.addEventListener('click', () => {
            loginTab.classList.add('active');
            registerTab.classList.remove('active');
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
        });

        registerTab.addEventListener('click', () => {
            registerTab.classList.add('active');
            loginTab.classList.remove('active');
            registerForm.style.display = 'block';
            loginForm.style.display = 'none';
        });

const emailInput = document.getElementById('register-email');
const status = document.getElementById('email-status');

let timeout = null;

emailInput.addEventListener('input', () => {
    clearTimeout(timeout);

    timeout = setTimeout(() => {
        const email = emailInput.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!email) {
            status.textContent = '';
            status.className = 'email-status';
            return;
        }

        if (!emailRegex.test(email)) {
            status.textContent = '❌ Format email invalide';
            status.className = 'email-status error';
            return;
        }

        status.textContent = 'Vérification...';
        status.className = 'email-status loading';

        fetch('check_email.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email })
        })
        .then(res => res.json())
        .then(data => {
            if (data.exists) {
                status.textContent = '❌ Email déjà utilisé';
                status.className = 'email-status error';
            } else {
                status.textContent = '✅ Email disponible';
                status.className = 'email-status success';
            }
        });
    }, 500);
});


    <!-- VALIDATION MOT DE PASSE -->

      const passwordInput = document.getElementById('register-password');
      const confirmPasswordInput = document.getElementById('register-confirm-password');
      const submitBtn = document.getElementById('submit-btn');

      const requirements = {
        length: { regex: /.{8,}/, element: document.getElementById('req-length') },
        uppercase: { regex: /[A-Z]/, element: document.getElementById('req-uppercase') },
        lowercase: { regex: /[a-z]/, element: document.getElementById('req-lowercase') },
        number: { regex: /[0-9]/, element: document.getElementById('req-number') },
        special: { regex: /[!@#$%^&*(),.?":{}|<>]/, element: document.getElementById('req-special') }
      };

      function checkPasswordStrength(password) {
        let validCount = 0;

        for (const [key, req] of Object.entries(requirements)) {
          if (req.regex.test(password)) {
            req.element.classList.add('valid');
            req.element.classList.remove('invalid');
            req.element.querySelector('.icon').textContent = '✓';
            validCount++;
          } else {
            req.element.classList.add('invalid');
            req.element.classList.remove('valid');
            req.element.querySelector('.icon').textContent = '✗';
          }
        }

        const strengthBar = document.getElementById('strength-bar');
        strengthBar.className = 'password-strength-bar';

        if (validCount <= 2) {
          strengthBar.classList.add('strength-weak');
        } else if (validCount <= 4) {
          strengthBar.classList.add('strength-medium');
        } else {
          strengthBar.classList.add('strength-strong');
        }

        return validCount === 5;
      }

      function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const matchDiv = document.getElementById('password-match');

        if (!confirmPassword) {
          matchDiv.textContent = '';
          matchDiv.className = 'password-match';
          return false;
        }

        if (password === confirmPassword) {
          matchDiv.textContent = '✓ Les mots de passe correspondent';
          matchDiv.className = 'password-match valid';
          return true;
        } else {
          matchDiv.textContent = '✗ Les mots de passe ne correspondent pas';
          matchDiv.className = 'password-match invalid';
          return false;
        }
      }

      function updateSubmitButton() {
        const passwordValid = checkPasswordStrength(passwordInput.value);
        const passwordsMatch = checkPasswordMatch();
        submitBtn.disabled = !(passwordValid && passwordsMatch);
      }

      passwordInput.addEventListener('input', () => {
        updateSubmitButton();
      });

      confirmPasswordInput.addEventListener('input', () => {
        updateSubmitButton();
      });

      // Validation lors de la soumission
      document.getElementById('register-form').addEventListener('submit', (e) => {
        const passwordValid = checkPasswordStrength(passwordInput.value);
        const passwordsMatch = checkPasswordMatch();

        if (!passwordValid || !passwordsMatch) {
          e.preventDefault();
          alert('Veuillez corriger les erreurs dans le formulaire avant de soumettre.');
        }
      });

  const params = new URLSearchParams(window.location.search);

  if (params.get('erreur') === '1') {
    document.getElementById('msg-erreur').style.display = 'block';
  }
    if (params.get('error') === 'carun') {
    document.getElementById('msg-erreur-car').style.display = 'block';
  }
    if (params.get('erreur') === '2') {
    document.getElementById('msg-erreur-email').style.display = 'block';
  }
   if (params.get('verified') === '1') {
    document.getElementById('msg-email-valide').style.display = 'block';
  }

      const loginTab = document.getElementById("login-tab");
      const registerTab = document.getElementById("register-tab");
      const loginForm = document.getElementById("login-form");
      const registerForm = document.getElementById("register-form");

        loginTab.addEventListener('click', () => {
            loginTab.classList.add('active');
            registerTab.classList.remove('active');
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
        });

        registerTab.addEventListener('click', () => {
            registerTab.classList.add('active');
            loginTab.classList.remove('active');
            registerForm.style.display = 'block';
            loginForm.style.display = 'none';
        });

const emailInput = document.getElementById('register-email');
const status = document.getElementById('email-status');

let timeout = null;

emailInput.addEventListener('input', () => {
    clearTimeout(timeout);

    timeout = setTimeout(() => {
        const email = emailInput.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!email) {
            status.textContent = '';
            status.className = 'email-status';
            return;
        }

        if (!emailRegex.test(email)) {
            status.textContent = '❌ Format email invalide';
            status.className = 'email-status error';
            return;
        }

        status.textContent = 'Vérification...';
        status.className = 'email-status loading';

        fetch('check_email.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email })
        })
        .then(res => res.json())
        .then(data => {
            if (data.exists) {
                status.textContent = '❌ Email déjà utilisé';
                status.className = 'email-status error';
            } else {
                status.textContent = '✅ Email disponible';
                status.className = 'email-status success';
            }
        });
    }, 500);
});

      let currentDate = new Date();
      let selectedDate = null;
      const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

      const today = new Date();
      const maxDate = new Date(today.getFullYear() - 16, today.getMonth(), today.getDate());

      document.getElementById('register-birthdate').addEventListener('click', function() {
        document.getElementById('calendar-modal').classList.add('active');
        populateYearSelector();
        renderCalendar();
      });

      function populateYearSelector() {
        const yearSelector = document.getElementById('year-selector');
        yearSelector.innerHTML = '';
        
        const currentYear = new Date().getFullYear();
        const startYear = currentYear - 100;
        const endYear = currentYear - 16;
        
        for (let year = endYear; year >= startYear; year--) {
          const option = document.createElement('option');
          option.value = year;
          option.textContent = year;
          if (year === currentDate.getFullYear()) {
            option.selected = true;
          }
          yearSelector.appendChild(option);
        }
      }

      function changeYear(increment) {
        currentDate.setFullYear(currentDate.getFullYear() + increment);
        document.getElementById('year-selector').value = currentDate.getFullYear();
        renderCalendar();
      }

      function changeToYear() {
        const selectedYear = parseInt(document.getElementById('year-selector').value);
        currentDate.setFullYear(selectedYear);
        renderCalendar();
      }

      function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        document.getElementById('monthYear').textContent = `${months[month]} ${year}`;
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = (firstDay.getDay() + 6) % 7;
        
        const calendarDays = document.getElementById('calendar-days');
        calendarDays.innerHTML = '';
        
        for (let i = 0; i < startingDayOfWeek; i++) {
          const emptyCell = document.createElement('div');
          emptyCell.className = 'day-cell empty';
          calendarDays.appendChild(emptyCell);
        }
        
        for (let day = 1; day <= daysInMonth; day++) {
          const dayCell = document.createElement('div');
          dayCell.className = 'day-cell';
          dayCell.textContent = day;
          
          const cellDate = new Date(year, month, day);
          
          //désactiver dates > 16
          if (cellDate > maxDate) {
            dayCell.classList.add('disabled');
          } else {
            if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
              dayCell.classList.add('today');
            }
            
            dayCell.onclick = () => selectDate(day, month, year, dayCell, cellDate);
          }
          
          calendarDays.appendChild(dayCell);
        }
      }

      function selectDate(day, month, year, element, cellDate) {
        if (cellDate > maxDate) {
          document.getElementById('age-error').classList.add('show');
          return;
        }
        
        document.getElementById('age-error').classList.remove('show');
        
        document.querySelectorAll('.day-cell.selected').forEach(cell => {
          cell.classList.remove('selected');
        });
        
        element.classList.add('selected');
        selectedDate = new Date(year, month, day);
      }

      function changeMonth(increment) {
        currentDate.setMonth(currentDate.getMonth() + increment);
        renderCalendar();
      }

      function validateDate() {
        if (!selectedDate) {
          alert('Veuillez sélectionner une date');
          return;
        }
        
        if (selectedDate > maxDate) {
          document.getElementById('age-error').classList.add('show');
          return;
        }
        
        const day = String(selectedDate.getDate()).padStart(2, '0');
        const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
        const year = selectedDate.getFullYear();
        
        const formattedDate = `${day}/${month}/${year}`;
        const isoDate = `${year}-${month}-${day}`;
        
        document.getElementById('register-birthdate').value = formattedDate;
        document.getElementById('birthdate-hidden').value = isoDate;
        
        closeCalendar();
      }

      function closeCalendar() {
        document.getElementById('calendar-modal').classList.remove('active');
        selectedDate = null;
      }

      // Fermer en cliquant en dehors
      document.getElementById('calendar-modal').addEventListener('click', function(e) {
        if (e.target === this) {
          closeCalendar();
        }
      });