import { useEffect, useState } from "react";
import skillHubFacade from "../services/skillHubFacade";
import { courseSortStrategies, filterByCategory } from "../strategies/courseSortStrategies";
import CourseCard from "../components/CourseCard";
import "./CoursesPage.css";

function CoursesPage() {
  const [courses, setCourses] = useState([]);
  const [categories, setCategories] = useState([]);
  const [sortStrategy, setSortStrategy] = useState("title");
  const [selectedCategory, setSelectedCategory] = useState("");
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    skillHubFacade
      .getCourses()
      .then((res) => {
        setCourses(res.data);
        setLoading(false);
      })
      .catch(() => {
        setCourses([]);
        setLoading(false);
      });

    skillHubFacade
      .getCategories()
      .then((res) => {
        setCategories(res.data);
      })
      .catch(() => {
        setCategories([]);
      });
  }, []);

  const selectedStrategy = courseSortStrategies[sortStrategy];
  const sortedCourses = selectedStrategy
    ? selectedStrategy(courses)
    : courses;
  const filteredCourses = filterByCategory(sortedCourses, selectedCategory);

  if (loading) return <p className="loading">Loading courses...</p>;

  return (
    <div className="courses-page">
      <h1>All Courses</h1>

      <div className="filters">
        <select
          value={sortStrategy}
          onChange={(e) => setSortStrategy(e.target.value)}
        >
          <option value="title">Sort by Title</option>
          <option value="priceLow">Price: Low to High</option>
          <option value="priceHigh">Price: High to Low</option>
        </select>

        <select
          value={selectedCategory}
          onChange={(e) => setSelectedCategory(e.target.value)}
        >
          <option value="">All Categories</option>
          {categories.map((cat) => (
            <option key={cat.id} value={cat.id}>
              {cat.name}
            </option>
          ))}
        </select>
      </div>

      <div className="courses-grid">
        {filteredCourses.length > 0 ? (
          filteredCourses.map((course) => (
            <CourseCard key={course.id} course={course} />
          ))
        ) : (
          <p>No courses found.</p>
        )}
      </div>
    </div>
  );
}

export default CoursesPage;