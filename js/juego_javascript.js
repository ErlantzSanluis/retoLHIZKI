//Estado del juego
const gameState = {
  preguntas: [],
  currentQuestion: 0,
  lives: 3,
  correctAnswers: 0,
  wrongAnswers: 0,
  startTime: null,
  totalQuestions: 10,
}

// Elementos del DOM
const loadingScreen = document.getElementById("loadingScreen")
const gameScreen = document.getElementById("gameScreen")
const resultsScreen = document.getElementById("resultsScreen")
const questionWord = document.getElementById("questionWord")
const answerInput = document.getElementById("answerInput")
const submitBtn = document.getElementById("submitBtn")
const feedbackMessage = document.getElementById("feedbackMessage")
const progressBar = document.getElementById("progressBar")
const progressText = document.getElementById("progressText")

// Inicializar el juego
document.addEventListener("DOMContentLoaded", () => {
  initGame()

  // Event listeners
  submitBtn.addEventListener("click", checkAnswer)

  answerInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter" && !submitBtn.disabled) {
      checkAnswer()
    }
  })

  // Limpiar feedback al escribir
  answerInput.addEventListener("input", () => {
    answerInput.classList.remove("correct", "incorrect")
    feedbackMessage.classList.remove("show")
  })
})

/**
 * Inicializar el juego
 */
async function initGame() {
  try {
    showScreen("loading")

    // Obtener preguntas del servidor
    const response = await fetch("../controller/juego_controller.php?accion=obtener_preguntas")
    const data = await response.json()

    if (data.success && data.preguntas && data.preguntas.length >= 10) {
      gameState.preguntas = data.preguntas
      gameState.startTime = Date.now()

      // Pequeño delay para mostrar la animación de carga
      setTimeout(() => {
        showScreen("game")
        loadQuestion()
      }, 1500)
    } else {
      showError("Ez dira nahikoa galdera aurkitu. Saiatu berriro geroago.")
    }
  } catch (error) {
    console.error("Error al inicializar el juego:", error)
    showError("Errorea gertatu da jokoa hastean. Saiatu berriro.")
  }
}

/**
 * Mostrar una pantalla específica
 */
function showScreen(screen) {
  loadingScreen.classList.remove("active")
  gameScreen.classList.remove("active")
  resultsScreen.classList.remove("active")

  switch (screen) {
    case "loading":
      loadingScreen.classList.add("active")
      break
    case "game":
      gameScreen.classList.add("active")
      break
    case "results":
      resultsScreen.classList.add("active")
      break
  }
}

/**
 * Cargar pregunta actual
 */
function loadQuestion() {
  if (gameState.currentQuestion >= gameState.totalQuestions) {
    endGame()
    return
  }

  const pregunta = gameState.preguntas[gameState.currentQuestion]
  questionWord.textContent = pregunta.termino_castellano

  // Resetear input
  answerInput.value = ""
  answerInput.classList.remove("correct", "incorrect")
  answerInput.disabled = false
  answerInput.focus()

  // Resetear botón
  submitBtn.disabled = false

  // Ocultar feedback
  feedbackMessage.classList.remove("show")

  // Actualizar progreso
  updateProgress()
}

/**
 * Comprobar respuesta
 */
function checkAnswer() {
  const userAnswer = normalizeString(answerInput.value.trim())
  const correctAnswer = normalizeString(gameState.preguntas[gameState.currentQuestion].respuesta_correcta)

  // Deshabilitar input y botón
  answerInput.disabled = true
  submitBtn.disabled = true

  if (userAnswer === correctAnswer) {
    handleCorrectAnswer()
  } else {
    handleWrongAnswer(gameState.preguntas[gameState.currentQuestion].respuesta_correcta)
  }
}

/**
 * Manejar respuesta correcta
 */
function handleCorrectAnswer() {
  gameState.correctAnswers++

  // Feedback visual
  answerInput.classList.add("correct")
  showFeedback("Ondo! 🎉", "correct")

  // Pasar a la siguiente pregunta
  setTimeout(() => {
    gameState.currentQuestion++
    loadQuestion()
  }, 1500)
}

/**
 * Manejar respuesta incorrecta
 */
function handleWrongAnswer(correctAnswer) {
  gameState.wrongAnswers++
  gameState.lives--

  // Feedback visual
  answerInput.classList.add("incorrect")
  showFeedback(`Oker! Erantzun zuzena: ${correctAnswer}`, "incorrect")

  // Actualizar vidas
  updateLives()

  // Comprobar si quedan vidas
  if (gameState.lives <= 0) {
    setTimeout(() => {
      endGame()
    }, 2000)
  } else {
    // Pasar a la siguiente pregunta
    setTimeout(() => {
      gameState.currentQuestion++
      loadQuestion()
    }, 2000)
  }
}

/**
 * Mostrar mensaje de feedback
 */
function showFeedback(message, type) {
  feedbackMessage.textContent = message
  feedbackMessage.className = `feedback-message show ${type}`
}

/**
 * Actualizar vidas
 */
function updateLives() {
  const lifeIcons = document.querySelectorAll(".life-icon")
  lifeIcons.forEach((icon, index) => {
    if (index >= gameState.lives) {
      icon.classList.add("lost")
    }
  })
}

/**
 * Actualizar barra de progreso
 */
function updateProgress() {
  const progress = (gameState.currentQuestion / gameState.totalQuestions) * 100
  progressBar.style.width = `${progress}%`
  progressText.textContent = `${gameState.currentQuestion}/${gameState.totalQuestions}`
}

/**
 * Finalizar el juego
 */
async function endGame() {
  const timeElapsed = Math.floor((Date.now() - gameState.startTime) / 1000)
  const points = gameState.correctAnswers * 100

  // Guardar resultado en el servidor
  try {
    const formData = new FormData()
    formData.append("accion", "guardar_resultado")
    formData.append("aciertos", gameState.correctAnswers)
    formData.append("fallos", gameState.wrongAnswers)
    formData.append("tiempo_empleado", timeElapsed)

    await fetch("../controller/juego_controller.php", {
      method: "POST",
      body: formData,
    })
  } catch (error) {
    console.error("Error al guardar resultado:", error)
  }

  // Mostrar pantalla de resultados
  showResults(points)
}

/**
 * Mostrar resultados
 */
function showResults(points) {
  // Actualizar estadísticas
  document.getElementById("correctAnswers").textContent = gameState.correctAnswers
  document.getElementById("wrongAnswers").textContent = gameState.wrongAnswers
  document.getElementById("totalPoints").textContent = points

  // Personalizar mensaje según rendimiento
  const resultsIcon = document.getElementById("resultsIcon")
  const resultsTitle = document.getElementById("resultsTitle")

  if (gameState.correctAnswers >= 9) {
    resultsIcon.textContent = "🏆"
    resultsTitle.textContent = "Bikain!"
  } else if (gameState.correctAnswers >= 7) {
    resultsIcon.textContent = "🌟"
    resultsTitle.textContent = "Oso ondo!"
  } else if (gameState.correctAnswers >= 5) {
    resultsIcon.textContent = "👍"
    resultsTitle.textContent = "Ondo!"
  } else {
    resultsIcon.textContent = "💪"
    resultsTitle.textContent = "Jarraitu praktikatzen!"
  }

  // Mostrar pantalla de resultados
  showScreen("results")
}

/**
 * Reiniciar el juego
 */
function restartGame() {
  // Resetear estado
  gameState.currentQuestion = 0
  gameState.lives = 3
  gameState.correctAnswers = 0
  gameState.wrongAnswers = 0
  gameState.startTime = null

  // Resetear vidas visuales
  const lifeIcons = document.querySelectorAll(".life-icon")
  lifeIcons.forEach((icon) => {
    icon.classList.remove("lost")
  })

  // Reinicializar
  initGame()
}

/**
 * Mostrar error
 */
function showError(message) {
  alert(message)
  location.href = "perfilAlumno.php"
}

/**
 * Normalizar string para comparación (sin tildes ni mayúsculas)
 */
function normalizeString(str) {
  return str
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .trim()
}