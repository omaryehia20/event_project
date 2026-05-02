const { useState, useMemo } = React;

const eventsData = window.EVENTLY_EVENTS || [];

function formatEventDate(date) {
  return new Date(date).toLocaleDateString(undefined, {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  });
}

function EventCard({ event }) {
  return (
    <div className="card">
      <img src={`images/${event.image}`} className="event-img" alt={event.title} />
      <h3 style={{ color: 'white' }}>{event.title}</h3>
      <p style={{ color: 'white' }}>{event.description}</p>
      <p style={{ color: 'white' }}><strong>Location:</strong> {event.location}</p>
      <p style={{ color: 'white' }}><strong>Date:</strong> {formatEventDate(event.date)}</p>
      <p className="price">${event.price}</p>
      <div className="buy-container">
        <a href={`checkout.php?event_id=${event.id}`} className="buy-btn">Buy Ticket</a>
      </div>
    </div>
  );
}

function EventFilterApp() {
  const [query, setQuery] = useState('');
  const [location, setLocation] = useState('all');
  const [date, setDate] = useState('');

  const locations = useMemo(() => {
    const set = new Set(eventsData.map((event) => event.location));
    return ['all', ...Array.from(set).sort()];
  }, []);

  const filteredEvents = useMemo(() => {
    return eventsData.filter((event) => {
      const normalizedQuery = query.trim().toLowerCase();
      const matchesQuery =
        !normalizedQuery ||
        event.title.toLowerCase().includes(normalizedQuery) ||
        event.description.toLowerCase().includes(normalizedQuery) ||
        event.location.toLowerCase().includes(normalizedQuery);

      const matchesLocation = location === 'all' || event.location === location;
      const matchesDate = !date || event.date === date;

      return matchesQuery && matchesLocation && matchesDate;
    });
  }, [query, location, date]);

  return (
    <div className="evently-app">
      <div className="evently-filter-panel">
        <div className="evently-search-group">
          <input
            type="search"
            className="evently-search"
            placeholder="Search by title, location, or description..."
            value={query}
            onChange={(event) => setQuery(event.target.value)}
          />
          <select
            className="evently-select"
            value={location}
            onChange={(event) => setLocation(event.target.value)}
          >
            {locations.map((loc) => (
              <option key={loc} value={loc}>
                {loc === 'all' ? 'All Locations' : loc}
              </option>
            ))}
          </select>
          <input
            type="date"
            className="evently-date"
            value={date}
            onChange={(event) => setDate(event.target.value)}
          />
        </div>

        <div className="evently-summary">
          <strong>{filteredEvents.length}</strong> event{filteredEvents.length === 1 ? '' : 's'} found
        </div>
      </div>

      {filteredEvents.length ? (
        <div className="events-grid">
          {filteredEvents.map((event) => (
            <EventCard key={event.id} event={event} />
          ))}
        </div>
      ) : (
        <div className="event-empty">No events match your search and filters.</div>
      )}
    </div>
  );
}

ReactDOM.createRoot(document.getElementById('events-root')).render(<EventFilterApp />);
