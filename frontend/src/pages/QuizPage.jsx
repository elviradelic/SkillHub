import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import skillHubFacade from "../services/skillHubFacade";
import "./QuizPage.css";

function QuizPage() {
  const { courseId } = useParams();

  const [quiz, setQuiz] = useState(null);
  const [questions, setQuestions] = useState([]);
  const [answers, setAnswers] = useState({});
  const [score, setScore] = useState(null);
  const [earnedPoints, setEarnedPoints] = useState(null);
  const [totalPoints, setTotalPoints] = useState(null);
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(true);

  const user = JSON.parse(localStorage.getItem("user"));

  useEffect(() => {
    skillHubFacade
      .getQuizByCourse(courseId)
      .then((res) => {
        const quizData = res.data.data;
        setQuiz(quizData);

        if (quizData && quizData.id) {
          return skillHubFacade.getQuizQuestions(quizData.id);
        }

        return null;
      })
      .then((res) => {
        if (res) {
          setQuestions(res.data.data || []);
        }
        setLoading(false);
      })
      .catch(() => {
        setError("Failed to load quiz.");
        setLoading(false);
      });
  }, [courseId]);

  const handleAnswerChange = (questionId, value) => {
    setAnswers({
      ...answers,
      [questionId]: value,
    });
  };

  const handleSubmit = () => {
    if (!user) {
      setError("Please log in to submit quiz.");
      return;
    }

    if (!quiz) {
      setError("Quiz not found.");
      return;
    }

    skillHubFacade
      .submitQuizWithAnswers({
        user_id: user.id,
        quiz_id: quiz.id,
        answers: answers,
      })
      .then((res) => {
        const result = res.data.data;

        setScore(result.score);
        setEarnedPoints(result.earned_points);
        setTotalPoints(result.total_points);
      })
      .catch(() => {
        setError("Failed to submit quiz result.");
      });
  };

  if (loading) return <p className="loading">Loading quiz...</p>;
  if (error) return <p className="error">{error}</p>;
  if (!quiz) return <p className="error">No quiz available for this course.</p>;

  return (
    <div className="quiz-page">
      <h1>{quiz.title}</h1>
      <p className="quiz-duration">Duration: {quiz.duration} minutes</p>

      <div className="questions-list">
        {questions.length > 0 ? (
          questions.map((question, index) => (
            <div key={question.id} className="question-card">
              <p className="question-text">
                {index + 1}. {question.text}
              </p>
              <p className="question-points">Points: {question.points}</p>

              <input
                type="text"
                className="question-input"
                placeholder="Enter your answer"
                value={answers[question.id] || ""}
                onChange={(e) =>
                  handleAnswerChange(question.id, e.target.value)
                }
              />
            </div>
          ))
        ) : (
          <p>No questions available.</p>
        )}
      </div>

      {score !== null ? (
        <div className="quiz-result">
          <h2>Quiz Submitted!</h2>
          <p>Your score: {score}%</p>
          <p>
            Points: {earnedPoints} / {totalPoints}
          </p>

          <a href="/results" className="btn-results">
            View All Results
          </a>
        </div>
      ) : (
        <button className="btn-submit-quiz" onClick={handleSubmit}>
          Submit Quiz
        </button>
      )}
    </div>
  );
}

export default QuizPage;