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
        setResults(res.data.data);
        setLoading(false);
      })
      .catch(() => {
        setError("Failed to load results.");
        setLoading(false);
      });
  }, []);

  if (loading) return <p className="loading">Loading results...</p>;
  if (error) return <p className="error">{error}</p>;

  return (
    <div className="results-page">
      <h1>My Results</h1>

      {results.length === 0 ? (
        <p>No quiz results found.</p>
      ) : (
        <div className="results-list">
          {results.map((result) => (
            <div key={result.id} className="result-card">
              <h2>{result.quiz_title}</h2>
              <p>Course: {result.course_title}</p>
              <p className="result-score">Score: {result.score}%</p>
              <p className="result-date">Date: {result.date_taken}</p>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}

export default ResultsPage;