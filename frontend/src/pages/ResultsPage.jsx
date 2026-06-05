import { useEffect, useState } from "react";
import skillHubFacade from "../services/skillHubFacade";
import "./ResultsPage.css";

function ResultsPage() {
  const [results, setResults] = useState([]);
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(true);

  const user = JSON.parse(localStorage.getItem("user"));

  useEffect(() => {
    if (!user) {
      setError("Please log in to view results.");
      setLoading(false);
      return;
    }

    skillHubFacade
      .getUserResults(user.id)
      .then((res) => {
        setResults(res.data.data || []);
        setLoading(false);
      })
      .catch(() => {
        setError("Failed to load results.");
        setLoading(false);
      });
  }, []);

  if (loading) return <p className="loading">Loading results...</p>;
  if (error) return <p className="error">{error}</p>;

  const totalResults = results.length;
  const averageScore =
    totalResults > 0
      ? (
          results.reduce((sum, result) => sum + Number(result.score || 0), 0) /
          totalResults
        ).toFixed(1)
      : 0;

  const bestScore =
    totalResults > 0
      ? Math.max(...results.map((result) => Number(result.score || 0)))
      : 0;

  return (
    <div className="results-page">
      <div className="results-hero">
        <p className="results-kicker">Learning Performance</p>
        <h1>My Results</h1>
        <p>Track your quiz scores and monitor your learning progress.</p>
      </div>

      <div className="results-stats">
        <div className="results-stat-card">
          <span>Total Quizzes</span>
          <strong>{totalResults}</strong>
        </div>

        <div className="results-stat-card">
          <span>Average Score</span>
          <strong>{averageScore}%</strong>
        </div>

        <div className="results-stat-card">
          <span>Best Score</span>
          <strong>{bestScore}%</strong>
        </div>
      </div>

      <section className="results-panel">
        <div className="results-section-header">
          <h2>Quiz History</h2>
          <p>All completed quizzes are listed below.</p>
        </div>

        {results.length === 0 ? (
          <p className="results-empty">No quiz results found.</p>
        ) : (
          <div className="results-grid">
            {results.map((result) => (
              <div key={result.id} className="result-card">
                <div className="result-card-top">
                  <div>
                    <h3>{result.quiz_title}</h3>
                    <p>{result.course_title}</p>
                  </div>

                  <span className="result-score-badge">
                    {Number(result.score || 0).toFixed(2)}%
                  </span>
                </div>

                <div className="result-progress">
                  <div
                    style={{
                      width: `${Number(result.score || 0)}%`,
                    }}
                  />
                </div>

                <p className="result-date">Date: {result.date_taken}</p>
              </div>
            ))}
          </div>
        )}
      </section>
    </div>
  );
}

export default ResultsPage;