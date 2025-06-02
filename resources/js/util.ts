export function filterSortPaginate<T>(
  items: T[],
  config: {
    page: number;
    perPage: number;
    searchQuery: string;
    sortBy: string;
    sortOrder: 'asc' | 'desc';
  },
  searchFields: string[]
): T[] {
  const query = config.searchQuery.toLowerCase();

  // Filter
  let filtered = items.filter(item =>
    searchFields.some(field => {
      const value = getValue(item, field);
      return typeof value === 'string' && value.toLowerCase().includes(query);
    })
  );

  if (filtered.length === 0) return [];

  // Sort
  filtered.sort((a, b) => {
    const aValue = getValue(a, config.sortBy);
    const bValue = getValue(b, config.sortBy);

    if (aValue < bValue) return config.sortOrder === 'asc' ? -1 : 1;
    if (aValue > bValue) return config.sortOrder === 'asc' ? 1 : -1;
    return 0;
  });

  // Paginate
  const start = (config.page - 1) * config.perPage;
  return filtered.slice(start, start + config.perPage);
}

function getValue(obj: any, path: string) {
    return path.split('.').reduce((acc, part) => acc && acc[part], obj);
}