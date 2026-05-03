export const sortByTitle = (courses) => {
  return [...courses].sort((a, b) => a.title.localeCompare(b.title));
};

export const sortByPriceLowToHigh = (courses) => {
  return [...courses].sort((a, b) => Number(a.price) - Number(b.price));
};

export const sortByPriceHighToLow = (courses) => {
  return [...courses].sort((a, b) => Number(b.price) - Number(a.price));
};

export const filterByCategory = (courses, categoryId) => {
  if (!categoryId || categoryId === "") return courses;

  return courses.filter(
    (course) => String(course.category_id) === String(categoryId)
  );
};

export const courseSortStrategies = {
  title: sortByTitle,
  priceLow: sortByPriceLowToHigh,
  priceHigh: sortByPriceHighToLow,
};

